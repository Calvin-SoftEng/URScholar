from flask import Flask, request, jsonify
from flask_cors import CORS
import logging
import numpy as np
from sklearn.ensemble import RandomForestClassifier

# -----------------------------------------------------
# CONFIG
# -----------------------------------------------------
app = Flask(__name__)
CORS(app)

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# -----------------------------------------------------
# AI ENGINE (Random Forest)
# -----------------------------------------------------
class ScholarshipRecommender:
    def __init__(self):
        self.model = None
        # In a real app, you would load a saved file: model = joblib.load('scholar_model.pkl')
        # Here, we train a dummy model on startup just to make the code work.
        self._train_dummy_model()

    def _train_dummy_model(self):
        """
        Trains a fake model so the API works immediately.
        Features: [Grade (float), Income_Level (int)]
        Target: 1 (High chance of winning), 0 (Low chance)
        """
        # Fake historical data: [Grade, Income_Level]
        # Income Level: 1=Low, 2=Medium, 3=High
        X_train = np.array([
            [1.2, 1], [1.5, 1], [1.1, 1], # Good grades, Low income -> Won
            [2.5, 3], [3.0, 3], [2.8, 2], # Low grades, High income -> Lost
            [1.5, 2], [1.7, 1]            # Decent grades -> Won
        ])
        y_train = np.array([1, 1, 1, 0, 0, 0, 1, 1]) # 1 = Won, 0 = Lost

        self.model = RandomForestClassifier(n_estimators=100, random_state=42)
        self.model.fit(X_train, y_train)
        logger.info("🤖 Random Forest Model initialized and trained on dummy data.")

    def predict_score(self, grade, income_str):
        """
        Returns a probability score (0% to 100%) of winning the scholarship.
        """
        try:
            # 1. Preprocess Grade
            g_val = float(grade)
            
            # 2. Preprocess Income (Simple mapping for this demo)
            # In production, use OneHotEncoder or LabelEncoder
            income_map = {"Below 10k": 1, "10k-20k": 1, "20k-50k": 2, "Above 50k": 3}
            i_val = income_map.get(income_str, 2) # Default to Medium if unknown

            # 3. Predict Probability
            # features must match X_train shape: [Grade, Income]
            features = np.array([[g_val, i_val]])
            
            # predict_proba returns [[prob_loss, prob_win]]
            score = self.model.predict_proba(features)[0][1] 
            return round(score * 100, 2)
            
        except Exception as e:
            logger.error(f"AI Prediction failed: {e}")
            return 0.0

# Initialize the AI
ai_engine = ScholarshipRecommender()

# -----------------------------------------------------
# DATA MODELS
# -----------------------------------------------------

def validate_scholar(data):
    return {
        "scholar_id": data.get("scholar_id"),
        "campus_id": data.get("campus_id"),
        "course_id": data.get("course_id"),
        "grade": data.get("grade"), 
        "family_monthly_income": data.get("family_monthly_income"), 
    }

def validate_scholarship(data):
    grade_limit = data.get("required_grade_limit")
    if grade_limit is None and "criteriaData" in data:
        grade_limit = data["criteriaData"].get("grade")

    return {
        "id": data.get("id"),
        "name": data.get("name"),
        "deadline": data.get("deadline"),
        "required_grade_limit": grade_limit,
        "required_course_id": data.get("required_course_id"),
        "monthly_income_limits": data.get("monthly_income_limits", []), 
        "campus_recipient_ids": data.get("campus_recipient_ids", []),
    }

# -----------------------------------------------------
# ELIGIBILITY CHECK LOGIC (Hard Rules)
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
        limit = scholarship["required_grade_limit"]
        if limit is not None and limit != "":
            scholar_grade = self.scholar.get("grade")
            if scholar_grade is None:
                return {"eligible": False, "reason": "No grade record."}

            try:
                s_grade_float = float(scholar_grade)
                limit_float = float(limit)
                
                # Logic: Lower is Better (1.0 > 2.0)
                if s_grade_float > limit_float:
                     return {"eligible": False, "reason": f"GWA ({s_grade_float}) lower than ({limit_float})."}
            except ValueError:
                return {"eligible": False, "reason": "Invalid grade format."}

        # 3. Course
        if scholarship["required_course_id"]:
            if self.scholar["course_id"] != scholarship["required_course_id"]:
                return {"eligible": False, "reason": "Wrong course."}

        # 4. Income
        income_limits = scholarship.get("monthly_income_limits", [])
        if income_limits:
            scholar_income = self.scholar.get("family_monthly_income")
            if not scholar_income:
                return {"eligible": False, "reason": "No income record."}
            if scholar_income not in income_limits:
                 return {"eligible": False, "reason": "Income not allowed."}

        return {"eligible": True, "reason": "Eligible"}


# -----------------------------------------------------
# ENDPOINTS
# -----------------------------------------------------

@app.route("/", methods=["GET"])
def root():
    return jsonify({"status": "ok", "message": "Scholarship AI Filter Running"})

@app.route("/non-grantee/eligibility", methods=["POST"])
def filter_scholarships():
    try:
        req = request.get_json()

        # Validate Inputs
        scholar = validate_scholar(req.get("scholar", {}))
        scholarships = [validate_scholarship(s) for s in req.get("scholarships", [])]

        eligible = []
        not_eligible = []

        # 1. HARD FILTERING (Rule Based)
        checker = EligibilityChecker(scholar)

        for scholarship in scholarships:
            result = checker.check(scholarship)
            
            base = {
                "scholarship_id": scholarship["id"],
                "name": scholarship["name"],
                "deadline": scholarship["deadline"]
            }

            if result["eligible"]:
                # 2. AI SCORING (Random Forest)
                # Only run expensive AI on people who actually passed the rules
                match_score = ai_engine.predict_score(
                    scholar.get("grade"), 
                    scholar.get("family_monthly_income")
                )

                eligible.append({
                    **base, 
                    "reason": "Eligible", 
                    "match_score": match_score # e.g., 85.50%
                })
            else:
                not_eligible.append({**base, "reason": result["reason"]})

        # 3. SORTING
        # Sort eligible scholarships by match_score (Highest % first)
        eligible.sort(key=lambda x: x["match_score"], reverse=True)

        logger.info(f"Processed {len(scholarships)} items. {len(eligible)} eligible.")

        return jsonify({
            "eligible": eligible,
            "not_eligible": not_eligible
        })

    except Exception as e:
        logger.error(f"ERROR: {str(e)}")
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)