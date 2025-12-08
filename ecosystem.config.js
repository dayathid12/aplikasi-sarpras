module.exports = {
  apps : [{
    name   : "laravel-dev-watcher", // Nama aplikasi Anda
    script : "npm",                // Perintah yang akan dijalankan
    args   : "run dev",            // Argumen untuk perintah tersebut
    cwd    : "./",                 // Direktori kerja (root proyek Anda)
    watch  : true,                 // Awasi perubahan file untuk restart otomatis
    ignore_watch : ["node_modules", "public", "vendor", "storage"], // Abaikan folder ini dari pengawasan
    env: {
      "NODE_ENV": "development",
    },
  }]
};