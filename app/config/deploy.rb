set :application, "Le-Cerf-Vert"
set :domain,      "ssh.lecerfvert.net"
set :deploy_to,   "/home/lecerfvert.net/www/Le-Cerf-Vert"
set :app_path,    "app"
set :user, "lecerfvert.net"

set :repository,  "git@github.com:jleveau/Le_Cerf_Vert.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :deploy_via, :copy

set :model_manager, "doctrine"
# Or: `propel`
set :use_sudo, false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set :writable_dirs, ["app/cache", "app/logs"]

set :use_composer, true
set :use_set_permissions, true
set :dump_assetic_assets, true

set  :keep_releases,  3

logger.level = Logger::MAX_LEVEL
# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL