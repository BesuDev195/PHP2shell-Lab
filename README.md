# Techtonic Bank — PHP2Shell: The Diagnostic Gateway (CTF Challenge)

An enterprise banking-themed cybersecurity training laboratory demonstrating operating system command injection (CWE-78) in modern PHP applications.

> [!WARNING]
> **INTENTIONALLY VULNERABLE LAB APPLICATION**  
> This web application contains an intentional OS command injection vulnerability designed strictly for authorized cybersecurity education, penetration testing practice, and defensive secure-coding instruction. All customer profiles, transaction histories, balances, and credentials are synthetic. Never deploy this container onto an untrusted or publicly exposed network.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Challenge Scenario](#2-challenge-scenario)
3. [Application Architecture](#3-application-architecture)
4. [Prerequisites for Ubuntu](#4-prerequisites-for-ubuntu)
5. [Building the Docker Image](#5-building-the-docker-image)
6. [Starting and Stopping the Application](#6-starting-and-stopping-the-application)
7. [Accessing the Local Website](#7-accessing-the-local-website)
8. [Configuring an Authorized Challenge IP](#8-configuring-an-authorized-challenge-ip)
9. [Inspecting Docker Logs](#9-inspecting-docker-logs)
10. [Resetting the Challenge and Restoring the Flag](#10-resetting-the-challenge-and-restoring-the-flag)
11. [Troubleshooting Port Conflicts](#11-troubleshooting-port-conflicts)
12. [Verifying Service Reachability](#12-verifying-service-reachability)
13. [Network Hardening and Firewall Configuration](#13-network-hardening-and-firewall-configuration)
14. [Security Warnings & Residual Risk Analysis](#14-security-warnings--residual-risk-analysis)
15. [Tear Down and Clean Up](#15-tear-down-and-clean-up)

---

## 1. Project Overview

- **Application Name:** Techtonic Bank (Techtonic Bank)
- **Challenge Title:** PHP2Shell — The Diagnostic Gateway
- **Category:** Web Application Security / OS Command Injection
- **Vulnerability Identifier:** CWE-78 (Improper Neutralization of Special Elements used in an OS Command)
- **Target Audience:** Security students, penetration testers, red/blue teams, and application developers.
- **Estimated Difficulty:** Beginner to Intermediate

Techtonic Bank presents a corporate institutional banking interface with enterprise styling (navy, crisp white, and restrained gold accents). Behind this portal lies an internal administrative infrastructure diagnostic gateway (`/admin/diagnostics.php`) designed to troubleshoot network reachability between core banking clusters. The legacy probe implementation passes unsanitized user inputs to a shell execution function, permitting command injection within an unprivileged sandbox container.

---

## 2. Challenge Scenario

The infrastructure team at Techtonic Bank deployed a web-based network reachability probe to allow operations personnel to verify round-trip packet latency to internal microservices and gateways (`db-cluster.internal`, `core-gateway.ctc.local`, etc.).

During an internal security review, an auditor discovers that the diagnostic handler constructs its ICMP echo commands using direct string concatenation without argument escaping or input allowlisting:

```php
// File: app/diagnostic.php (Lines 57-61)
// INTENTIONAL VULNERABILITY:
$cmd = "ping -c 2 " . $target . " 2>&1";
$output = shell_exec($cmd);
```

### Challenge Objectives

1. Identify the input parameter accepted by the diagnostic probe (`target`).
2. Analyze how shell delimiters (such as `;`, `&&`, `||`, `|`, `$(...)`, or `` `...` ``) break out of the intended `ping` syntax.
3. Execute controlled commands as the unprivileged application user (`ctcbank`).
4. Locate the synthetic challenge flag stored on the container filesystem (`/challenge/flag.txt`).
5. Validate the captured flag using the interactive validator at `/challenge.php`.
6. Understand how the vulnerability is remediated using `escapeshellarg()` or native PHP sockets.

---

## 3. Application Architecture

```
ctc-bank/
├── Dockerfile              # Hardened Alpine-based PHP 8.3 container specification
├── compose.yaml            # Docker Compose orchestration with confinement limits
├── .dockerignore           # Build exclusion rules
├── README.md               # Complete deployment and training manual
├── challenge/
│   └── flag.txt            # Synthetic challenge flag (restricted permissions)
├── app/
│   ├── config.php          # Session settings, environment constants, security flags
│   ├── auth.php            # Session authentication, login verification, ID regeneration
│   ├── demo_data.php       # Synthetic accounts, transactions, and diagnostic history
│   ├── functions.php       # Output escaping, currency formatting, badge helpers
│   ├── diagnostic.php      # Diagnostic execution engine (contains intentional CWE-78)
│   └── layout/
│       ├── header.php      # Corporate topbar, navigation breadcrumbs, training banner
│       ├── sidebar.php     # Banking sidebar navigation with security status card
│       └── footer.php      # Corporate footer with disclaimers
└── public/
    ├── index.php           # Routing entrypoint
    ├── login.php           # Corporate sign-in page with demo credentials
    ├── logout.php          # Session termination handler
    ├── dashboard.php       # Treasury dashboard with balances and activity summaries
    ├── accounts.php        # Commercial accounts portfolio and record filtering
    ├── transactions.php    # Transaction audit ledger with search and status filters
    ├── challenge.php       # Flag validator and submission endpoint
    ├── 403.php             # Polished 403 Forbidden error page
    ├── 404.php             # Polished 404 Not Found error page
    ├── 500.php             # Polished 500 Server Error page
    ├── router.php          # Built-in PHP server router and path confinement
    ├── admin/
    │   └── diagnostics.php # Primary CTF challenge endpoint (Diagnostic Gateway)
    └── assets/
        ├── css/
        │   ├── bank.css    # Core enterprise banking design system
        │   └── diagnostics.css # Terminal console and diagnostic styles
        ├── js/
        │   ├── app.js      # Sidebar toggle, table filtering, clipboard copy
        │   └── diagnostics.js # Console loading animations and preset handlers
        └── images/
            ├── logo.svg    # Techtonic Bank vector emblem & wordmark
            └── favicon.svg # Vector favicon
```

### Defense-in-Depth Container Controls

| Confinement Layer | Implementation Details |
|---|---|
| **Base Image** | `php:8.3-cli-alpine` (minimal packages, no compiler, minimal footprint) |
| **Execution User** | Dedicated non-root user `ctcbank` (UID 10001, GID 10001) |
| **Privilege Escalation** | `no-new-privileges:true` active via Docker security options |
| **Linux Capabilities** | All capabilities dropped (`cap_drop: ALL`); only `NET_RAW` granted for ICMP probes |
| **Filesystem State** | Read-only container root (`read_only: true`); writable storage limited to in-memory `tmpfs` mounts (`/tmp`, `/var/tmp`) |
| **Process Limits** | PIDs limit capped at 100 to prevent local fork bombs |
| **Memory Limit** | Hard memory cap of 256MB (`deploy.resources.limits.memory`) |
| **Network Interface** | Bound strictly to `127.0.0.1:8080` by default |

---

## 4. Prerequisites for Ubuntu

Ensure your Ubuntu system (20.04 LTS, 22.04 LTS, 24.04 LTS, or newer) has Docker and Docker Compose installed:

```bash
# Update local packages
sudo apt update && sudo apt install -y curl ca-certificates gnupg

# Verify Docker installation
docker --version
docker compose version
```

If Docker is not installed, install it using the official Docker repository:

```bash
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

Ensure your current user belongs to the `docker` group (or run with `sudo`):

```bash
sudo usermod -aG docker $USER
newgrp docker
```

---

## 5. Building the Docker Image

Navigate to the project root directory and build the Docker image:

```bash
cd "/run/media/hyperfly/New Volume/PHP2shell-Lab"

# Build image using Docker Compose
docker compose build
```

Alternatively, to build directly using the Docker CLI:

```bash
docker build -t ctc-bank-lab:latest .
```

---

## 6. Starting and Stopping the Application

### Start the Container (Detached Mode)

```bash
docker compose up -d
```

### Verify Container Status

```bash
docker compose ps
```

Expected output:
```
NAME           IMAGE               COMMAND                  SERVICE    CREATED          STATUS                    PORTS
ctc-bank-lab   php2shell-lab-ctc-bank   "php -S 0.0.0.0:8080…"   ctc-bank   10 seconds ago   Up 9 seconds (healthy)    127.0.0.1:8080->8080/tcp
```

### Stop the Container

```bash
docker compose down
```

To stop without removing container state:

```bash
docker compose stop
```

To resume execution:

```bash
docker compose start
```

---

## 7. Accessing the Local Website

Once the container is running, open your web browser or test with `curl`:

- **Portal URL:** [http://127.0.0.1:8080](http://127.0.0.1:8080)
- **Flag Validator:** [http://127.0.0.1:8080/challenge.php](http://127.0.0.1:8080/challenge.php)
- **Diagnostic Challenge Target:** [http://127.0.0.1:8080/admin/diagnostics.php](http://127.0.0.1:8080/admin/diagnostics.php)

### Demo Authentication Credentials

The application includes two pre-configured synthetic user roles:

| Role | Username | Password | Access Clearance |
|---|---|---|---|
| **Infrastructure Administrator** | `admin` | `Techtonic@Admin2026!` | Level 4: Full Infrastructure & Diagnostic Gateway |
| **Senior Treasury Officer** | `officer_chen` | `SecureBank123!` | Level 2: Accounts & Settlement Ledger |

> [!NOTE]
> The login interface provides quick "Auto Fill" buttons for rapid lab testing.

---

## 8. Configuring an Authorized Challenge IP

By default, `compose.yaml` binds the challenge port strictly to loopback:

```yaml
ports:
  - "127.0.0.1:8080:8080"
```

If deploying the challenge on a dedicated lab server accessible across an isolated training subnet (e.g. `192.168.100.10`), adjust the host binding in `compose.yaml`:

```yaml
ports:
  - "192.168.100.10:8080:8080"
```

Then restart the service:

```bash
docker compose up -d --force-recreate
```

> [!CAUTION]
> Never bind the service to `0.0.0.0` on an untrusted or internet-facing network interface. Anyone with network reachability could execute arbitrary commands inside the container sandbox.

---

## 9. Inspecting Docker Logs

To follow application server logs in real time:

```bash
docker compose logs -f
```

To view the last 50 log lines:

```bash
docker compose logs --tail=50
```

To view container lifecycle events and health check status:

```bash
docker inspect --format='{{json .State.Health}}' ctc-bank-lab
```

---

## 10. Resetting the Challenge and Restoring the Flag

If a student tampers with container files or you wish to completely restore the training environment to its pristine initial state, rebuild and recreate the container:

```bash
# 1. Stop and tear down the running container
docker compose down

# 2. Re-create the container with clean temporary filesystems
docker compose up -d --force-recreate
```

### Synthetic Flag Location

The challenge flag is stored at `/challenge/flag.txt` inside the container:

- **Filesystem Path:** `/challenge/flag.txt`
- **Ownership:** `root:ctcbank`
- **Permissions:** `0640` (Read-only for `ctcbank` group)
- **Flag Format:** `techtonic{c0mm4nd_1nj3ct10n_php2sh3ll_g4t3w4y_3xpl01t}`

To verify or change the flag on the host before building:

```bash
cat challenge/flag.txt
```

---

## 11. Troubleshooting Port Conflicts

If port `8080` is already in use by another service on your host:

```bash
# Identify process using port 8080
sudo ss -tulpn | grep :8080
# or
sudo lsof -i :8080
```

To resolve the conflict without altering internal container configurations, change the host port mapping in `compose.yaml`:

```yaml
ports:
  - "127.0.0.1:8088:8080"   # Maps host port 8088 to container port 8080
```

Restart the container:

```bash
docker compose up -d
```

Access the application at [http://127.0.0.1:8088](http://127.0.0.1:8088).

---

## 12. Verifying Service Reachability

Use `curl` to test service endpoints directly from your shell:

```bash
# Check that HTTP returns 302 or 200
curl -I http://127.0.0.1:8080/login.php

# Check that the diagnostic page is reachable
curl -s http://127.0.0.1:8080/login.php | grep -i "Techtonic Bank"
```

To verify the container healthcheck passes:

```bash
docker inspect --format='{{.State.Health.Status}}' ctc-bank-lab
```

Expected output:
```
healthy
```

---

## 13. Network Hardening and Firewall Configuration

When operating a CTF event or classroom lab, use Ubuntu's `ufw` or `iptables` to restrict incoming connections to authorized student IP addresses:

```bash
# Allow SSH access
sudo ufw allow 22/tcp

# Allow training challenge access only from an authorized classroom subnet
sudo ufw allow from 192.168.100.0/24 to any port 8080 proto tcp

# Enable firewall
sudo ufw enable
```

### Docker Network Note

Be aware that Docker bypasses standard `ufw` input chains by writing directly to `iptables` NAT tables. To ensure Docker respects your local firewall rules on Ubuntu:

1. Use explicit IP bindings in `compose.yaml` (e.g. `192.168.100.10:8080:8080` or `127.0.0.1:8080:8080`).
2. Alternatively, place the container behind an authenticating reverse proxy (such as NGINX or Traefik) configured with client IP allowlisting.

---

## 14. Security Warnings & Residual Risk Analysis

### Residual Risk Notice

Although this application is confined inside an unprivileged Docker container:
- The challenge **intentionally permits arbitrary operating system commands** within the container environment.
- Linux container isolation is not equivalent to a hardware virtual machine (VM). Kernel-level vulnerabilities in the host Linux kernel could theoretically be exploited by a malicious actor if given arbitrary command execution.
- Docker bridge networking alone does not prevent lateral network scanning across containers sharing the same bridge network unless explicitly isolated.

### Recommended Safety Rules

1. **Host Isolation:** Run this lab on a dedicated lab server, classroom VM, or disposable virtual machine.
2. **Never Mount Sockets:** Do not mount `/var/run/docker.sock` into this container.
3. **Never Grant Privileged Mode:** Never run this container with `--privileged`.
4. **No Sensitive Volumes:** Do not mount your home directory or host filesystem into the container.
5. **No Production Secrets:** Do not store genuine API keys, private certificates, or real credentials on the host server.

---

## 15. Tear Down and Clean Up

To completely remove the training container, network, and built image from your machine:

```bash
# Stop and remove container and associated network
docker compose down --volumes --remove-orphans

# Remove the built image
docker rmi php2shell-lab-ctc-bank:latest ctc-bank-lab:latest 2>/dev/null || true

# Prune unused build caches if desired
docker builder prune -f
```

---

**Developed for Authorized Cybersecurity Training & Penetration Testing Education.**  
&copy; Commercial &amp; Trust Capital (Techtonic Bank) Simulation Lab.