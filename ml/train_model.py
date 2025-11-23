import joblib

# Dummy model for now (simple function-like class)
# Replace with your real ML model later
class EligibilityModel:
    def predict(self, scholar, scholarship):
        # Always return eligible=True (you can update this)
        return True

model = EligibilityModel()

# Save model
joblib.dump(model, "non_grantee_model.joblib")

print("Model saved successfully as non_grantee_model.joblib")
