set :application, "teameval"
set :repository,  "https://subversion.assembla.com/svn/asu-teamwork-evaluation/trunk"
set :scm, :subversion

# Username, password
set :user, "cgthornt_cap"
# set :password, "CapTheStone12"

set :deploy_via, :copy
set :copy_cache, true
set :deploy_to, "/home/cgthornt_cap/teameval"

set :copy_strategy, :export
set :use_sudo, false

role :web, "sod73.asu.edu"
role :app, "sod73.asu.edu"
role :db,  "sod73.asu.edu", :primary => true 

# if you want to clean up old releases on each deploy uncomment this:
after "deploy:restart", "deploy:cleanup"

# After setup, use our own after setup config
after "deploy:setup", "capstone:after_setup"

# before "deploy:create_symlink", "capstone:get_access_to_yii"
before "deploy:create_symlink", "capstone:copy_configs"
before "deploy:create_symlink", "capstone:execute_composer"
before "deploy:create_symlink", "capstone:run_migrations"
before "deploy:create_symlink", "capstone:get_access_to_runtime"


default_run_options[:pty] = true


namespace :capstone do
  
  # Copies any configuration options to overwrite any in the /config dir
  task :copy_configs do
    run "cp -R #{shared_path}/configs/* #{release_path}/config"
  end
  
  # Remove the .composer directory, symlinked the shared directory and execute composer
  task :execute_composer do
    run "rm -rf #{release_path}/.composer"
    run "ln -s #{shared_path}/composer #{release_path}/.composer"
    run "cd #{release_path} && php composer.phar install"
  end
  
  # Gets access to the yii directory
  task :get_access_to_yii do
    run "ln -s #{shared_path}/yii #{release_path}/framework"
  end
  
  # Get access to folders
  # @todo: make this more secure!
  task :get_access_to_runtime do
    run "cd #{release_path} && chmod 777 runtime"
    run "cd #{release_path} && chmod 777 public/assets"
    run "cd #{release_path} && chmod 777 public/ui/less/compiled"
  end
  
  # Run Database migrations
  task :run_migrations do
    run "cd #{release_path} && php yiic.php migrate --interactive=0"
  end
  
  # Create shared/composer and shared/configs dirs for between releases
  task :after_setup do
    run "mkdir #{shared_path}/composer"
    run "mkdir #{shared_path}/configs"
    run "mkdir #{shared_path}/yii"
  end
end

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end

