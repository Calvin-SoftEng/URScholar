from sklearn.datasets import load_iris
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from joblib import dump

# 1. Load sample dataset (Iris)
X, y = load_iris(return_X_y=True)

# 2. Split dataset
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 3. Train model
clf = RandomForestClassifier()
clf.fit(X_train, y_train)

# 4. Save model with Joblib
dump(clf, "model.joblib")

print("✅ Model trained and saved as model.joblib")
