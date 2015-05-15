# Contribution Guidelines

## Bug Reports

To encourage active collaboration, we strongly encourages pull requests, not just bug reports. "Bug reports" may also be sent in the form of a pull request.

However, if you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and an example that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

The #GreenAlert source code is managed on Github, and there are repositories for each of the components:

- [#GreenAlert Web Platform](https://github.com/CodeForAfrica/GreenAlert)
- [Flat-UI CSS ](https://github.com/DavidLemayian/Flat-UI)

## Core Development Discussion

Discussion regarding bugs, new features, and implementation of existing features takes place in the #greenalert-dev IRC channel (Freenode). David Lemayian, the maintainer of #GreenAlert, is typically present in the channel on weekdays from 8am-5pm (EAT+03:00 or Africa/Naiorbi), and sporadically present in the channel at other times.

The #greenalert-dev IRC channel is open to all. All are welcome to join the channel either to participate or simply observe the discussions!


## Which Branch?

To make branching easy, we follow [Gitflow Workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow).It defines a strict branching model designed around the project release.

All bug fixes should be sent to the latest stable branch. Bug fixes should never be sent to the master branch unless they fix features that exist only in the upcoming release.

Minor features that are fully backwards compatible with the current Laravel release may be sent to the latest stable branch.

Major new features should always be sent to the master branch, which contains the upcoming Laravel release.

If you are unsure if your feature qualifies as a major or minor, please ask David Lemayian in the #greenalert-dev IRC channel (Freenode).


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to us at support@codeforafrica.org. All security vulnerabilities will be promptly addressed.


## Coding Style

\#GreenAlert follows the PSR-4 and PSR-1 coding standards. In addition to these standards, the following coding standards should be followed:

- The class namespace declaration must be on the same line as `<?php`.
- A class' opening `{` must be on the same line as the class name.
- Functions and control structures must use *Allman style* braces.
- Indent with soft tabs, align with spaces.

As a rule of thumb, we use [Google's style guide](https://code.google.com/p/google-styleguide/).
