from flask import Flask, request, jsonify
from flask_cors import CORS
import logging

# -----------------------------------------------------
# CONFIG
# -----------------------------------------------------
app = Flask(__name__)
CORS(app)

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# -----------------------------------------------------
# DATA MODELS (structural validation)
# -----------------------------------------------------

def validate_scholar(data):
    return {
        "scholar_id": data.get("scholar_id"),
        "campus_id": data.get("campus_id"),
        "course_id": data.get("course_id"),
        "grade": data.get("grade"),
        # Ensure this is passed as a string from PHP
        "family_monthly_income": data.get("family_monthly_income"), 
    }

def validate_scholarship(data):
    return {
        "id": data.get("id"),
        "name": data.get("name"),
        "deadline": data.get("deadline"),
        "required_grade_limit": data.get("required_grade_limit"),
        "required_course_id": data.get("required_course_id"),
        
        # REVISED: Now accepts a list of strings, defaults to empty list
        "monthly_income_limits": data.get("monthly_income_limits", []), 
        
        "campus_recipient_ids": data.get("campus_recipient_ids", []),
    }

# -----------------------------------------------------
# ELIGIBILITY CHECK LOGIC
# -----------------------------------------------------

class EligibilityChecker:
    def __init__(self, scholar):
        self.scholar = scholar

    def check(self, scholarship):
        # 1. Campus
        if scholarship["campus_recipient_ids"]:
            if self.scholar["campus_id"] not in scholarship["campus_recipient_ids"]:
                return {"eligible": False, "reason": "Campus not a recipient."}

        # 2. Grade
        if scholarship["required_grade_limit"]:
            if self.scholar["grade"] is None:
                return {"eligible": False, "reason": "No grade record."}
            # Assuming lower is better (1.0 is better than 2.0), or standard logic
            # If your logic is "Higher is better", flip this operator. 
            # Usually scholarships require grade <= 2.5 (numeric value).
            if self.scholar["grade"] > scholarship["required_grade_limit"]:
                return {"eligible": False, "reason": "GWA does not meet required limit."}

        # 3. Course
        if scholarship["required_course_id"]:
            if self.scholar["course_id"] != scholarship["required_course_id"]:
                return {"eligible": False, "reason": "Course does not match required course."}

        # 4. Income (REVISED FOR STRING MATCHING)
        income_limits = scholarship.get("monthly_income_limits", [])
        
        # Only check if the scholarship actually has income restrictions
        if income_limits:
            scholar_income = self.scholar.get("family_monthly_income")

            if not scholar_income:
                return {"eligible": False, "reason": "No family income record."}

            # Check if the scholar's specific income string exists in the allowed list
            # Example: "Below 10,000" in ["Below 10,000", "10,000 - 20,000"]
            if scholar_income not in income_limits:
                 return {"eligible": False, "reason": f"Income '{scholar_income}' is not allowed."}

        return {"eligible": True, "reason": "Eligible"}


# -----------------------------------------------------
# ROOT + HEALTH ENDPOINTS
# -----------------------------------------------------

@app.route("/", methods=["GET"])
def root():
    logger.info("Root accessed")
    return jsonify({
        "status": "ok",
        "message": "Flask Scholarship Filter is running",
        "version": "1.1",
        "endpoints": {
            "health": "/health",
            "eligibility": "/non-grantee/eligibility"
        }
    })


@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "healthy", "service": "scholarship-filter"})


# -----------------------------------------------------
# MAIN ELIGIBILITY ENDPOINT
# -----------------------------------------------------

@app.route("/non-grantee/eligibility", methods=["POST"])
def filter_scholarships():
    try:
        req = request.get_json()

        # Validate Inputs
        scholar = validate_scholar(req.get("scholar", {}))
        scholarships = [validate_scholarship(s) for s in req.get("scholarships", [])]

        logger.info("=" * 70)
        logger.info("NEW ELIGIBILITY REQUEST")
        logger.info(f"Scholar ID: {scholar.get('scholar_id')}")
        logger.info(f"Scholar Income: {scholar.get('family_monthly_income')}")
        logger.info(f"Scholarships to check: {len(scholarships)}")
        logger.info("=" * 70)

        eligible = []
        not_eligible = []

        checker = EligibilityChecker(scholar)

        for idx, scholarship in enumerate(scholarships, 1):
            
            result = checker.check(scholarship)

            base = {
                "scholarship_id": scholarship["id"],
                "name": scholarship["name"],
                "deadline": scholarship["deadline"]
            }

            if result["eligible"]:
                logger.info(f"[{idx}] {scholarship['name']} -> ELIGIBLE")
                eligible.append({**base, "reason": "Eligible"})
            else:
                logger.info(f"[{idx}] {scholarship['name']} -> NOT ELIGIBLE ({result['reason']})")
                not_eligible.append({**base, "reason": result["reason"]})

        logger.info("=" * 70)
        logger.info(f"Eligible Count: {len(eligible)}")
        logger.info("=" * 70)

        return jsonify({
            "eligible": eligible,
            "not_eligible": not_eligible
        })

    except Exception as e:
        logger.error(f"ERROR: {str(e)}")
        # Return strict JSON error so Laravel doesn't crash trying to parse HTML
        return jsonify({"error": str(e), "eligible": [], "not_eligible": []}), 500


# -----------------------------------------------------
# RUN
# -----------------------------------------------------

if __name__ == "__main__":
    print("\n" + "=" * 70)
    print("🚀 Flask Scholarship Filter Server STARTED")
    print("📍 Server: http://127.0.0.1:5000")
    print("=" * 70 + "\n")

    app.run(host="0.0.0.0", port=5000, debug=True)