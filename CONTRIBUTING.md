# How to contribute
Thank you for considering to contribute to this repository! This file will walk you through all the steps to ensure both
you and I have a good time submitting and reviewing your contribution. First off, some basic rules and reading material:

- Submit your work in a new branch and make the PR to the master.
- [Write a short & descriptive commit message](http://chris.beams.io/posts/git-commit/).
- Rebase before committing the final change.
- Stick to [PSR-2](http://www.php-fig.org/psr/psr-2/).
- Add tests if necessary and ensure all the tests are green on the final commit.
- Make sure the CI tools used by the repository are all in order. If one fails, you should make it pass.

## Contributing
Here are the steps to follow to contribute to this repository:

- [Fork this repository on GitHub](#fork-this-repository).
- [Clone your fork to your local machine](#clone-your-fork).
- [Create a feature branch](#create-a-branch).
- [Add an 'upstream' remote](#add-a-remote).
- [Regularly pull & rebase from the upstream remote](#pull-and-rebase).
- [Work on feature branch](#working-on-branch).
- [Submit a pull request to the master branch](#submitting-pull-request).

### Fork this repository
Fork the repository right here on GitHub. To learn more about forking a repository, visit
[GitHub's help article](https://help.github.com/articles/fork-a-repo/).

### Clone your fork
Clone your repository on your local machine to start work on your pull request.

```bash
$ git clone git@github.com:<USERNAME>/<REPOSITORY>.git
# Or if you prefer HTTPS:
$ git clone https://github.com/<USERNAME>/<REPOSITORY>.git

$ cd <REPOSITORY>
```

### Create a branch
Make your own feature branch in order not to clutter up master.

```bash
# Think of a good name for your branch:
#   fix/typo-in-readme
#   feature/some-feature
#   bug/some-bug-you-are-fixing
$ git checkout -b <BRANCH_NAME>
```

### Add a remote
Add an 'upstream' remote to pull from and to stay up to date with the work being done in there.

```bash
# List all current remotes
$ git remote -v
origin  git@github.com/<USERNAME>/<REPOSITORY>.git (fetch)
origin  git@github.com/<USERNAME>/<REPOSITORY>.git (push)

# Add a new remote called 'upstream'
$ git remote add upstream git@github.com:svenluijten/<REPOSITORY>.git
# Or if you prefer HTTPS:
$ git remote add upstream https://github.com/svenluijten/<REPOSITORY>.git

# The new remote should now be in the list
$ git remote -v
origin  git@github.com/<USERNAME>/<REPOSITORY>.git (fetch)
origin  git@github.com/<USERNAME>/<REPOSITORY>.git (push)
upstream  git@github.com/svenluijten/<REPOSITORY>.git (fetch)
upstream  git@github.com/svenluijten/<REPOSITORY>.git (push)
```

### Pull and rebase
Pull from upstream to stay up to date with what others might be doing in this repository. Any merge conflicts that arise
are your responsibility to resolve.

```bash
$ git pull --rebase upstream master
```

### Working on branch
Do your magic and make your fix. I can't help you with this :wink:. Once you're happy with the result and all tests pass,
go ahead and push to your feature branch.

```bash
$ git push origin <BRANCH_NAME>
```

### Submitting pull request
Now, let's head back over to this repository on GitHub and submit the pull request!
