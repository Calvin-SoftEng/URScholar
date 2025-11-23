# ml/onetime_rank_api.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd
import numpy as np
import logging
import os

app = Flask(__name__)
CORS(app)
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger("onetime_rank_api")

MODEL_PATH = os.path.join(os.path.dirname(__file__), "onetime_rf.joblib")

def load_model():
    if os.path.exists(MODEL_PATH):
        try:
            model = joblib.load(MODEL_PATH)
            logger.info(f"Loaded model from {MODEL_PATH}")
            return model
        except Exception as e:
            logger.error("Failed to load model: %s", e)
    logger.warning("Model not found. API will use fallback ranking (grade-based).")
    return None

MODEL = load_model()

@app.get("/")
def root():
    return {"status": "ok", "service": "onetime-rank-api"}

@app.get("/health")
def health():
    return {"status": "healthy"}

def prepare_dataframe(scholars):
    """
    Convert incoming scholars list into dataframe with required features.
    We'll use the following features (recommended):
      - grade (float)          => lower is better (GWA)
      - progress (%)           => 0..100
      - year_level (int)
      - family_monthly_income (float)
    If fields are missing they are filled with sensible defaults.
    """
    df = pd.DataFrame(scholars)
    # Ensure columns exist
    for col in ["grade", "progress", "year_level", "family_monthly_income"]:
        if col not in df.columns:
            df[col] = np.nan

    # Fill missing values with defaults:
    # grade: worst possible (higher numeric is worse here; choose 5.0)
    df["grade"] = df["grade"].fillna(5.0).astype(float)
    # progress in percent 0..100 -> keep as-is; if it's a 0..1 value, assume it's percent already
    df["progress"] = df["progress"].fillna(0.0).astype(float)
    df["year_level"] = df["year_level"].fillna(1).astype(int)
    df["family_monthly_income"] = df["family_monthly_income"].fillna(0.0).astype(float)

    return df

@app.post("/rank_onetime")
def rank_onetime():
    """
    Input JSON:
    {
      "scholars": [
         {"id": 1, "grade": 1.2, "progress": 100, "year_level": 2, "family_monthly_income": 1000, ...},
         ...
      ]
    }

    Output JSON:
    {
      "ranking": {
         "1": {"rank": 1, "percentage": 100, "score": 95.2},
         "2": {"rank": 2, "percentage": 90, "score": 88.1},
         ...
      }
    }
    """
    body = request.get_json(silent=True)
    if not body or "scholars" not in body:
        return jsonify({"error": "Missing 'scholars' in request body."}), 400

    scholars = body["scholars"]
    if not isinstance(scholars, list) or len(scholars) == 0:
        return jsonify({"ranking": {}})

    df = prepare_dataframe(scholars)
    # Save ids for mapping
    ids = df.get("id").astype(int).tolist()

    # Choose features for model
    X = df[["grade", "progress", "year_level", "family_monthly_income"]].values

    if MODEL is not None:
        try:
            # Model should output higher score = better candidate.
            scores = MODEL.predict(X)
            # If model predicts GWA-like lower-is-better, we can invert — but assume model was trained
            # so higher means better priority. If not, retrain accordingly.
        except Exception as e:
            logger.error("Model prediction failed: %s", e)
            scores = df["grade"].ravel() * -1.0  # fallback: lower grade -> higher score (invert)
    else:
        # fallback scoring:
        # Give higher score to lower GWA and higher progress:
        # score = ( (5 - grade) * 0.6 ) + (progress/100 * 0.4 )   scaled to 0..100
        grades = df["grade"].astype(float)
        progress = df["progress"].astype(float)
        # Normalize grade into 0..1 (assuming grade in 1.0..5.0)
        grade_norm = (5.0 - grades) / 4.0  # 1.0 -> 1.0, 5.0 -> 0.0
        scores = (grade_norm * 0.7 + (progress / 100.0) * 0.3) * 100.0

    # Build result dataframe
    res_df = pd.DataFrame({
        "id": ids,
        "score": scores
    })

    # Rank: higher score => better (rank 1)
    res_df = res_df.sort_values(by="score", ascending=False).reset_index(drop=True)
    res_df["rank"] = res_df.index + 1
    total = len(res_df)
    res_df["percentage"] = res_df["rank"].apply(lambda r: round(((total - r + 1) / total) * 100))

    # Build associative result keyed by scholar id (as string keys for PHP-friendly associative array)
    ranking = {}
    for _, row in res_df.iterrows():
        sid = str(int(row["id"]))
        ranking[sid] = {
            "rank": int(row["rank"]),
            "percentage": int(row["percentage"]),
            "score": float(row["score"])
        }

    return jsonify({"ranking": ranking})

if __name__ == "__main__":
    # Run on port 5001 to match controller's expected URL (change if desired)
    app.run(host="127.0.0.1", port=5001, debug=True)
