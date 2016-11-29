# config valid only for current version of Capistrano
lock "3.7.0.beta1"

set :application, "symfony-demo"
set :repo_url, "git@github.com:achntrl/symfony-demo.git"

set :stages, %w(production staging)
set :cache_path, "var/cache"
set :log_path, "var/logs"
# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, "/var/www/symfony-demo"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# append :linked_files, "nginx.access.log", "nginx.error.log"

# Default value for linked_dirs is []
append :linked_dirs, fetch(:log_path)

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 5

# after "deploy:starting", "deploy:empty_cache"
after "deploy:finished", "deploy:composer_install"
after "deploy:composer_install", "deploy:empty_cache"
