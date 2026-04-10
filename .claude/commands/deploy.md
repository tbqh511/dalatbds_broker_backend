Deploy the DalatBDS project to the production server on cPanel.

This works by:
1. Pushing to `main` branch on GitHub
2. Calling cPanel UAPI to "Update from Remote" (git pull on server)
3. Calling cPanel UAPI to trigger "Deploy HEAD Commit" (runs .cpanel.yml)

## Credentials

Read cPanel credentials from `.claude/cpanel.env` (file local, không commit vào git).
Format file:
```
CPANEL_HOST=nethost-2711.net.vn
CPANEL_PORT=2083
CPANEL_USER=qymxlvghhosting
CPANEL_TOKEN=your_api_token_here
CPANEL_REPO_PATH=/home/qymxlvghhosting/public_html/dalatbds.com
```

If the file doesn't exist, tell the user to create it with the above format and get
the API token from: cPanel > Security > Manage API Tokens > Create (name: "claude-deploy").

## Steps

1. Run `git status` to ensure working tree is clean; commit any pending changes first.

2. Check current branch. If not on `main`:
   - Run `git checkout main`
   - Run `git merge <previous-branch> --no-ff -m "Deploy: merge <branch> into main"`

3. Run `git push -u origin main`

4. Load credentials from `.claude/cpanel.env`

5. Call cPanel UAPI to update from remote (pull from GitHub):
```bash
curl -s -k \
  -H "Authorization: cpanel $CPANEL_USER:$CPANEL_TOKEN" \
  "https://$CPANEL_HOST:$CPANEL_PORT/execute/VersionControl/retrieve" \
  --data-urlencode "repository_root=$CPANEL_REPO_PATH"
```

6. Call cPanel UAPI to trigger deployment (runs .cpanel.yml):
```bash
curl -s -k \
  -H "Authorization: cpanel $CPANEL_USER:$CPANEL_TOKEN" \
  -X POST \
  "https://$CPANEL_HOST:$CPANEL_PORT/execute/VersionControlDeployment/create" \
  --data-urlencode "repository_root=$CPANEL_REPO_PATH"
```

7. Check the JSON response:
   - `"status":1` = thành công
   - `"status":0` = lỗi, show `errors` field
   
8. Report the deployment result to the user. Remind them they can monitor progress
   in cPanel > Git Version Control > Manage Repository > Pull or Deploy.
