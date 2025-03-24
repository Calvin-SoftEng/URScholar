#!/bin/bash

# Create the .env file using environment variable content
echo "$ENV_CONTENT" > /var/www/html/.env

# Start Apache server
apache2-foreground
