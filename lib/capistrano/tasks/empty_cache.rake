namespace :deploy do
    desc 'Empty cache'
    task :empty_cache do
        on 'ubuntu@62.4.19.72' do
            within release_path do
                execute "rm -rf #{release_path}/../*/#{fetch(:cache_path)}" ## Un peu dangereux
                execute "php #{release_path}/bin/console cache:clear --env=prod"
            end
        end
    end
end