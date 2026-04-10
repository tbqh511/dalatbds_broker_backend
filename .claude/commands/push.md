Push all current changes to GitHub on the current branch.

Steps:
1. Run `git status` to check the current state
2. Run `git diff --stat` to summarize what has changed
3. If there are staged or unstaged changes:
   - Ask the user for a commit message (or use a provided one from $ARGUMENTS if given)
   - Stage all tracked modified files: `git add -u`
   - Also stage any new untracked files that seem intentional (ask if uncertain)
   - Commit with the message
4. Run `git push -u origin <current-branch>` to push to GitHub
5. Report the result clearly: which branch was pushed, how many commits ahead

Note: This pushes to the **current branch** (not necessarily main). Use `/deploy` to push to main and trigger cPanel auto-deploy.
