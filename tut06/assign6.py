from flask import Flask, request, jsonify
import jwt
import datetime
from functools import wraps

app = Flask(__name__)
app.config['SECRET_KEY'] = '1234'  # Replace with your secure secret key

# Example user "database"
users = {
    'alice': {
        'password': 'alice123',
        'role': 'admin'
    },
    'bob': {
        'password': 'bob123',
        'role': 'user'
    }
}

def token_required(f):
    """Decorator to verify the JWT token."""
    @wraps(f)
    def decorated(*args, **kwargs):
        token = None
        # Extract token from Authorization header (Bearer token)
        auth_header = request.headers.get('Authorization', None)
        if auth_header and auth_header.startswith("Bearer "):
            token = auth_header.split(" ")[1]
        
        if not token:
            return jsonify({'message': 'Token is missing!'}), 401
        
        try:
            # Decode token using the secret key and HS256 algorithm
            data = jwt.decode(token, app.config['SECRET_KEY'], algorithms=["HS256"])
            # Optionally, attach the decoded data to the request context
            request.user = data
        except jwt.ExpiredSignatureError:
            return jsonify({'message': 'Token has expired!'}), 401
        except jwt.InvalidTokenError as e:
            return jsonify({'message': 'Invalid token!', 'error': str(e)}), 401
        
        return f(*args, **kwargs)
    return decorated

def roles_required(allowed_roles):
    """Decorator to enforce role-based access."""
    def decorator(f):
        @wraps(f)
        def decorated(*args, **kwargs):
            token = None
            auth_header = request.headers.get('Authorization', None)
            if auth_header and auth_header.startswith("Bearer "):
                token = auth_header.split(" ")[1]
            
            if not token:
                return jsonify({'message': 'Token is missing!'}), 401
            
            try:
                data = jwt.decode(token, app.config['SECRET_KEY'], algorithms=["HS256"])
                user_role = data.get('role')
                if user_role not in allowed_roles:
                    return jsonify({'message': f'Access denied for role: {user_role}'}), 403
                request.user = data
            except jwt.ExpiredSignatureError:
                return jsonify({'message': 'Token has expired!'}), 401
            except jwt.InvalidTokenError as e:
                return jsonify({'message': 'Invalid token!', 'error': str(e)}), 401
            
            return f(*args, **kwargs)
        return decorated
    return decorator

@app.route('/')
def home():
    """Root route showing a simple welcome message."""
    return "This is a JWT testing app"

@app.route('/login', methods=['POST'])
def login():
    """
    Authenticate a user and return a JWT token.
    Expected JSON input: { "username": "alice", "password": "alice123" }
    """
    auth_data = request.get_json()
    
    if not auth_data or not auth_data.get('username') or not auth_data.get('password'):
        return jsonify({'message': 'Username and password are required.'}), 400

    username = auth_data['username']
    password = auth_data['password']
    
    user = users.get(username)
    if not user or user['password'] != password:
        return jsonify({'message': 'Invalid credentials.'}), 401
    
    # Create a token with an expiration time (e.g., 30 minutes)
    token = jwt.encode({
        'username': username,
        'role': user['role'],
        'exp': datetime.datetime.utcnow() + datetime.timedelta(seconds=20)
    }, app.config['SECRET_KEY'], algorithm="HS256")
    
    return jsonify({'token': token})

@app.route('/protected', methods=['GET'])
@token_required
def protected():
    """A sample protected endpoint that requires a valid JWT token."""
    return jsonify({'message': 'This endpoint is accessible with a valid token!',
                    'user': request.user})

@app.route('/admin', methods=['GET'])
@roles_required(['admin'])
def admin_only():
    """An endpoint restricted to users with the 'admin' role."""
    return jsonify({'message': 'Welcome, admin!', 'user': request.user})

if __name__ == '__main__':
    app.run(debug=True)
