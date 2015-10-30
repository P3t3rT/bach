set :application, "Bach"
set :domain,      "vps002.teris.nl"
set :deploy_to,   "/var/www/html/jsb.termaten.eu"
set :app_path,    "app"

set :repository,  "git@github.com:P3t3rT/bach.git"
# set :branch,    "branch_name"                  #PT
set :scm,         :git

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

set :dump_assetic_assets, true
set :composer_bin, "composer.phar"                 #PT
set :use_composer, true
set :composer_options,  "--no-dev --verbose --prefer-source --optimize-autoloader"   #PT
#set :install_vendors, true                        #PT = default

set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs","vendor"]

set :writable_dirs, ["app/cache", "app/logs"]
set :webserver_user, "www-data"
set :permission_method, :chown
set :use_set_permissions, true
set :use_sudo, true                              #PT
set :user, "root"                                #PT

ssh_options[:forward_agent] = true
ssh_options[:port] = "65022"                     #PT
default_run_options[:pty] = true

# Custom tasks
namespace :deploy do
	# Apache needs to be restarted to make sure that the APC cache is cleared.
	# Memcached will be flushed to clear the memcached cache
	# This overwrites the :restart task in the parent config which is empty.
	desc "Restart Apache"
	task :restart, :except => { :no_release => true }, :roles => :app do
		run "echo 'flush_all' | nc localhost 11211"
		puts "--> Memcached successfully flushed".green
		run "sudo service apache2 restart"
        puts "--> Apache successfully restarted".green
	end
end



