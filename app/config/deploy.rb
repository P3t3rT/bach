set :application, "Bach"
set :domain,      "vps002.teris.nl"
set :deploy_to,   "/var/www/html/jsb.termaten.eu"
set :app_path,    "app"

set :repository,  "git@github.com:P3t3rT/bach.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

set :dump_assetic_assets, true
set :composer_bin, "composer.phar"  #PT
set :use_composer, true
set :composer_options,  "--no-dev --verbose --prefer-source --optimize-autoloader"   #PT
#set :install_vendors, true                        #PT = default

set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs","vendor"]

set :writable_dirs, ["app/cache", "app/logs"]
set :webserver_user, "www-data"
set :permission_method, :chown
set :use_set_permissions, true
set :use_sudo, false                              #PT
set :user, "root"                                #PT

ssh_options[:forward_agent] = true
ssh_options[:port] = "65022"                     #PT
default_run_options[:pty] = true
