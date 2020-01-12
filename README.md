# phpBB 3.2/3.3 Extension - LMDI Glossary

## Install

1. Download the latest release.
2. Unzip the downloaded release, and change the name of the folder to `gloss`.
3. In the `ext` directory of your phpBB board, create a new directory named `lmdi` (if it does not already exist).
4. Copy the `gloss` folder to `/ext/lmdi/`.
5. Navigate in the ACP to `Customise -> Manage extensions`.
6. Look for `LMDI Glossary` under the Disabled Extensions list, and click its `Enable` link.

Enable the feature in the ACP (Extension tab).
Some users dislike the tagging of terms in the posts. Therefore, there is an option 
to disable it individually in the UCP.

## Glossary entry edition
By default, only administrators have this permission set in their group. If you want to give the permission to moderators, you should set this permission to Yes in their group. You may also want to create a special group of glossary editors.

## Uninstall

1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `LMDI Glossary` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/lmdi/gloss` folder.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

