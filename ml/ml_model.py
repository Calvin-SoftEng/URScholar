from joblib import load
import sys
import json

# Load pre-trained model
model = load("model.joblib")

# Read input data (from Laravel)
input_data = json.loads(sys.argv[1])

# Example: model expects [age, salary]
prediction = model.predict([input_data])

# Print result to send back
print(json.dumps({"prediction": prediction.tolist()}))
