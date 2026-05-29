# Laravel K8s Demo

Aplikasi Laravel 12 sederhana untuk lab **Kubernetes Networking** — khususnya untuk memahami cara kerja NodePort Service.

## Endpoint yang Tersedia

| Method | Endpoint  | Deskripsi |
|--------|-----------|-----------|
| GET    | `/`       | Info Pod (hostname, IP, timestamp) |
| GET    | `/health` | Health check (untuk Kubernetes probe) |
| GET    | `/info`   | Info environment dan versi aplikasi |
| GET    | `/up`     | Health check bawaan Laravel |

---

## Struktur Project

```
laravel-app/
├── app/
│   └── Providers/
│       └── AppServiceProvider.php   # Service provider utama
├── bootstrap/
│   ├── app.php                      # Inisialisasi Laravel 12
│   └── providers.php                # Daftar service provider
├── config/
│   ├── app.php                      # Konfigurasi aplikasi
│   ├── cache.php                    # Konfigurasi cache
│   ├── database.php                 # Konfigurasi database
│   ├── filesystems.php              # Konfigurasi file storage
│   ├── logging.php                  # Konfigurasi logging
│   └── session.php                  # Konfigurasi session
├── docker/
│   └── apache.conf                  # Konfigurasi Apache Virtual Host
├── public/
│   ├── index.php                    # Entry point aplikasi
│   └── .htaccess                    # URL rewriting rules
├── routes/
│   ├── web.php                      # *** File utama — semua endpoint ada di sini ***
│   └── console.php                  # Perintah Artisan custom
├── storage/                         # Cache, session, log (auto-generated)
├── .env.example                     # Template environment variables
├── .gitignore                       # File yang diabaikan Git
├── .dockerignore                    # File yang diabaikan Docker build
├── artisan                          # CLI tool Laravel
├── composer.json                    # Dependensi PHP
└── Dockerfile                       # Image production
```

---

## Cara Menjalankan Lokal (via Docker)

### 1. Build Image

```bash
# Masuk ke folder project
cd laravel-app

# Build Docker image
docker build -t laravel-k8s-demo:v1 .
```

### 2. Jalankan Container

```bash
docker run -d \
  --name laravel-demo \
  -p 8080:80 \
  laravel-k8s-demo:v1
```

### 3. Test Endpoint

```bash
# Homepage — info Pod
curl http://localhost:8080/

# Health check
curl http://localhost:8080/health

# Info aplikasi
curl http://localhost:8080/info
```

### 4. Stop Container

```bash
docker stop laravel-demo && docker rm laravel-demo
```

---

## Cara Build dan Push ke Docker Hub

```bash
# Login ke Docker Hub
docker login

# Build dengan tag yang benar
docker build -t <username>/laravel-k8s-demo:v1 .

# Push ke registry
docker push <username>/laravel-k8s-demo:v1
```

Ganti `<username>` dengan username Docker Hub kamu.

---

## Deploy ke Kubernetes

### 1. Update image di deployment YAML

Edit file `laravel-deployment.yaml` (ada di folder parent):

```yaml
image: <username>/laravel-k8s-demo:v1
```

### 2. Apply ke cluster

```bash
# Dari folder 02-k8s-nodeport-laravel-lab/
kubectl apply -f laravel-deployment.yaml
kubectl apply -f laravel-nodeport.yaml
```

### 3. Cek status

```bash
# Cek Pod berjalan
kubectl get pods -l app=laravel-web

# Cek Service
kubectl get svc laravel-web

# Lihat log Pod
kubectl logs -l app=laravel-web
```

### 4. Test via NodePort

```bash
# Dapatkan IP Node
kubectl get nodes -o wide

# Test endpoint (port NodePort = 30080)
curl http://<NODE-IP>:30080/
curl http://<NODE-IP>:30080/health
curl http://<NODE-IP>:30080/info
```

---

## Memahami Response Endpoint

### GET /

```json
{
  "app": "laravel-web",
  "version": "v1",
  "environment": "kubernetes",
  "hostname": "laravel-web-7d4b9c-xk2p9",
  "ip": "10.244.0.15",
  "timestamp": "2026-05-29T07:00:00.000000Z"
}
```

> **Tips Lab**: Coba jalankan `curl` berkali-kali. Jika Deployment punya lebih dari 1 replica, kamu akan melihat `hostname` dan `ip` yang berbeda-beda — ini membuktikan Kubernetes melakukan load balancing antar Pod!

### GET /health

```json
{
  "status": "healthy"
}
```

> Endpoint ini dipakai oleh Kubernetes **liveness probe** dan **readiness probe** untuk mengecek apakah Pod masih berjalan normal.

### GET /info

```json
{
  "app_name": "Laravel K8s Demo",
  "app_env": "production",
  "hostname": "laravel-web-7d4b9c-xk2p9",
  "php_version": "8.3.x",
  "laravel_version": "12.x.x"
}
```

---

## Variabel Environment Kubernetes

Untuk inject environment variable dari ConfigMap/Secret ke Pod, tambahkan di `laravel-deployment.yaml`:

```yaml
env:
  - name: APP_NAME
    value: "Laravel K8s Demo"
  - name: APP_ENV
    value: "production"
  - name: APP_KEY
    valueFrom:
      secretKeyRef:
        name: laravel-secret
        key: app-key
  - name: LOG_CHANNEL
    value: "stderr"
```

---

## Catatan Penting

- **APP_KEY wajib di-set** sebelum menjalankan aplikasi
- **LOG_CHANNEL=stderr** direkomendasikan di Kubernetes agar log bisa dibaca via `kubectl logs`
- Untuk multi-replica, gunakan **Redis** untuk cache dan session agar data di-share antar Pod
