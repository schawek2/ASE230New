# From module3/code/1_Docker/4.Containerizing Laravel with Docker/docker/nginx/Dockerfile

FROM nginx:alpine

# Copy NGINX configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Create web root directory
RUN mkdir -p /var/www/html/public

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html
RUN chmod -R 755 /var/www/html

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

# Expose port 80
EXPOSE 80

# Start nginx
CMD ["nginx", "-g", "daemon off;"]
