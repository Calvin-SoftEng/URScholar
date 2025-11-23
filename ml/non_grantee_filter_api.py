from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Dict, Optional

app = FastAPI(title="Non-Grantee Scholarship Filter API")

# =========================================================================
# 1. Pydantic Data Models
# =========================================================================

class ScholarModel(BaseModel):
    scholar_id: int
    campus_id: int
    course_id: int
    grade: Optional[float] = None
    family_monthly_income: Optional[float] = None


class ScholarshipModel(BaseModel):
    id: int
    name: str
    deadline: Optional[str] = None

    required_grade_limit: Optional[float] = None
    required_course_id: Optional[int] = None
    monthly_income_limit: Optional[float] = None

    campus_recipient_ids: List[int]


class EligibilityRequest(BaseModel):
    scholar: ScholarModel
    scholarships: List[ScholarshipModel]
    # The Laravel code uses: grade, familyRecord, familyIncome
    # They are already embedded inside `scholar`


class EligibilityResponse(BaseModel):
    eligible: List[Dict]
    not_eligible: List[Dict]

# =========================================================================
# 2. Eligibility Logic
# =========================================================================

class EligibilityChecker:

    def __init__(self, scholar: ScholarModel):
        self.scholar = scholar

    def check(self, scholarship: ScholarshipModel) -> dict:
        """Return { eligible: bool, reason: str }"""

        # ------------------------------------
        # 1. Campus check (must be a recipient)
        # ------------------------------------
        if scholarship.campus_recipient_ids:
            if self.scholar.campus_id not in scholarship.campus_recipient_ids:
                return {"eligible": False, "reason": "Campus not a recipient."}

        # ------------------------------------
        # 2. Grade Check (GWA must be <= limit)
        # ------------------------------------
        if scholarship.required_grade_limit and scholarship.required_grade_limit > 0:

            if self.scholar.grade is None:
                return {"eligible": False, "reason": "No grade record."}

            if self.scholar.grade > scholarship.required_grade_limit:
                return {"eligible": False, "reason": "GWA does not meet required limit."}

        # ------------------------------------
        # 3. Course Check
        # ------------------------------------
        if scholarship.required_course_id:
            if self.scholar.course_id != scholarship.required_course_id:
                return {"eligible": False, "reason": "Course does not match required course."}

        # ------------------------------------
        # 4. Income Check
        # ------------------------------------
        if scholarship.monthly_income_limit and scholarship.monthly_income_limit > 0:

            if self.scholar.family_monthly_income is None:
                return {"eligible": False, "reason": "No family income record."}

            if self.scholar.family_monthly_income > scholarship.monthly_income_limit:
                return {"eligible": False, "reason": "Family income exceeds the limit."}

        # ------------------------------------
        # Passed All Checks
        # ------------------------------------
        return {"eligible": True, "reason": "Eligible"}

# =========================================================================
# 3. API Endpoint
# =========================================================================

@app.post("/non-grantee/eligibility", response_model=EligibilityResponse)
def filter_scholarships(req: EligibilityRequest):

    eligible = []
    not_eligible = []

    checker = EligibilityChecker(req.scholar)

    for scholarship in req.scholarships:

        result = checker.check(scholarship)

        base = {
            "scholarship_id": scholarship.id,
            "name": scholarship.name,
            "deadline": scholarship.deadline
        }

        if result["eligible"]:
            eligible.append({**base, "reason": "Eligible"})
        else:
            not_eligible.append({**base, "reason": result["reason"]})

    return {
        "eligible": eligible,
        "not_eligible": not_eligible
    }

# =========================================================================
# HOW TO RUN:
# uvicorn ml.non_grantee_filter_api:app --reload
# =========================================================================
