Deploy the DalatBDS project to the production server on cPanel.

This works by pushing to the `main` branch on GitHub. cPanel's Git Version Control automatically detects the push and runs the deployment tasks defined in `.cpanel.yml`.

Steps:
1. Run `git status` to ensure working tree is clean
2. If there are uncommitted changes, commit them first (ask for a commit message)
3. Check current branch with `git branch --show-current`
4. If not on `main`:
   - Ask the user to confirm merging current branch into main
   - Run `git checkout main`
   - Run `git merge <previous-branch> --no-ff -m "Deploy: merge <branch> into main"`
5. Run `git push -u origin main`
6. Report success and remind the user that cPanel will now automatically:
   - Copy files to the deploy directory
   - Run `composer install --no-dev`
   - Run `php artisan migrate --force`
   - Clear and rebuild config/route/view caches
   - Set correct permissions on storage/

Important: After pushing, check cPanel Git Version Control panel to monitor the deployment status.
