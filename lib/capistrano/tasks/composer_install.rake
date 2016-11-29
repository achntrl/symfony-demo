namespace :deploy do
    desc 'Install composer'
    task :composer_install do
        on 'ubuntu@62.4.19.72' do
            within release_path do
                execute "composer install --working-dir #{release_path} --no-interaction"
            end
        end
    end
end