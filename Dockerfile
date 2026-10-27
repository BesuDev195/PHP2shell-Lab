# ==============================================================================
# Techtonic Bank — PHP2Shell CTF Challenge
# Hardened Isolated Container Image
# ==============================================================================

FROM php:8.3-cli-alpine

# Metadata
LABEL maintainer="Techtonic Bank Cybersecurity Lab Team" \
      challenge.name="PHP2Shell — The Diagnostic Gateway" \
      challenge.version="2.4.1" \
      environment="training-simulation"

# Install minimal system tools (iputils-ping for ping probe, bash for interactive shells if injected)
RUN apk add --no-cache \
        bash \
        iputils-ping \
        ca-certificates \
        tzdata

# Create dedicated unprivileged application user and group
RUN addgroup -g 10001 -S ctcbank && \
    adduser -u 10001 -S -G ctcbank -h /home/ctcbank -s /bin/bash ctcbank

# Create application directory
WORKDIR /var/www/ctc-bank

# Copy application code
COPY app/ /var/www/ctc-bank/app/
COPY public/ /var/www/ctc-bank/public/

# Copy challenge flag to isolated directory outside web root
# Restrict permissions: owned by root:ctcbank, read-only for ctcbank group
COPY challenge/flag.txt /challenge/flag.txt
RUN chown -R root:ctcbank /challenge && \
    chmod 750 /challenge && \
    chmod 640 /challenge/flag.txt

# Secure application files: owned by root:ctcbank, read-only executable
RUN chown -R root:ctcbank /var/www/ctc-bank && \
    chmod -R 755 /var/www/ctc-bank

# Prepare writable temp directories for session storage
RUN mkdir -p /tmp/ctc_sessions && \
    chown -R ctcbank:ctcbank /tmp/ctc_sessions && \
    chmod 1777 /tmp/ctc_sessions

# Container Healthcheck
HEALTHCHECK --interval=20s --timeout=4s --start-period=5s --retries=3 \
    CMD wget -q -O - http://127.0.0.1:8080/login.php > /dev/null || exit 1

# Switch to unprivileged user
USER ctcbank

# Expose service port
EXPOSE 8080

# Run PHP built-in server with router
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/ctc-bank/public", "/var/www/ctc-bank/public/router.php"]
