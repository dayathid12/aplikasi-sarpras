module.exports = {
  apps : [{
    name   : "laravel-dev-watcher",
    script : "start-dev.bat",
    cwd    : "./",
    watch  : true,
    ignore_watch : ["node_modules", "public", "vendor", "storage"],
    env: {
      "NODE_ENV": "development",
    },
  }]
};
