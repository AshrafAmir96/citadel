# Semantic Versioning with GitLab CI/CD

This document explains how semantic versioning is implemented in the Citadel project using GitLab CI/CD pipelines.

## Overview

The project uses **semantic-release** to automatically:
- Analyze commit messages to determine version bumps
- Generate version numbers following semantic versioning (semver)
- Create release notes and changelogs
- Tag releases in GitLab
- Build and deploy versioned artifacts

## Commit Message Format

We follow the **Angular Conventional Commits** format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat**: A new feature (minor version bump)
- **fix**: A bug fix (patch version bump)
- **docs**: Documentation changes (patch version bump)
- **style**: Code style changes (patch version bump)
- **refactor**: Code refactoring (patch version bump)
- **perf**: Performance improvements (patch version bump)
- **test**: Adding or updating tests (no version bump)
- **chore**: Build process or auxiliary tool changes (no version bump)
- **ci**: CI/CD pipeline changes (no version bump)

### Breaking Changes

For breaking changes that require a major version bump:

```
feat!: remove deprecated API endpoint

BREAKING CHANGE: The /api/v1/old-endpoint has been removed. Use /api/v2/new-endpoint instead.
```

### Examples

```bash
# Patch version (1.0.0 → 1.0.1)
git commit -m "fix: resolve authentication timeout issue"

# Minor version (1.0.0 → 1.1.0)  
git commit -m "feat: add user profile management API"

# Major version (1.0.0 → 2.0.0)
git commit -m "feat!: redesign authentication system

BREAKING CHANGE: JWT tokens now expire after 1 hour instead of 24 hours."

# No version bump
git commit -m "docs: update API documentation"
git commit -m "test: add unit tests for user service"
git commit -m "chore: update dependencies"
```

## CI/CD Pipeline Stages

### 1. Version Stage

**semantic_release** (main branch only):
- Analyzes commit messages since last release
- Determines next version number
- Generates changelog
- Creates GitLab release
- Updates VERSION file

**version_info** (feature branches):
- Generates development version based on branch and commit
- Format: `v1.2.3-feature-branch.abc1234`

### 2. Build Stage

- Reads version from VERSION file
- Sets `APP_VERSION` environment variable
- Creates versioned deployment archive: `citadel-v1.2.3.tar.gz`
- Includes version in build artifacts

### 3. Deploy Stage

- Extracts versioned archive
- Deploys with proper version information
- Updates application with current version

## Version Access

### In Code

```php
// Get current application version
$version = app_version();

// Available in blade templates
{{ app_version() }}
```

### API Endpoint

```bash
curl https://your-domain.com/api/version
```

Response:
```json
{
  "version": "1.2.3",
  "timestamp": "2025-07-30T10:30:00.000000Z",
  "environment": "production"
}
```

### Environment Variable

```bash
# Set during CI/CD build process
APP_VERSION=1.2.3
```

## Branch Strategy

### Main Branch
- Triggers semantic release on every push
- Creates stable releases with proper version tags
- Deploys to production

### Develop Branch  
- Creates pre-release versions with `-beta` suffix
- Format: `v1.2.3-beta.1`
- Deploys to staging environment

### Feature Branches
- Creates development versions
- Format: `v1.2.3-feature-branch.abc1234`
- Used for review apps and testing

## Configuration Files

### .releaserc.json
```json
{
  "branches": ["main", {"name": "develop", "prerelease": "beta"}],
  "plugins": [
    "@semantic-release/commit-analyzer",
    "@semantic-release/release-notes-generator", 
    "@semantic-release/changelog",
    "@semantic-release/gitlab",
    "@semantic-release/git"
  ]
}
```

### GitLab CI Variables
```yaml
# Required for semantic-release
GITLAB_TOKEN: $CI_JOB_TOKEN  # Automatically provided by GitLab
```

## Version Helper Function

The `app_version()` helper function tries multiple sources in order:

1. **APP_VERSION** environment variable (CI/CD)
2. **VERSION** file in project root (build artifact)
3. **Git tags** with commit hash (development)
4. **package.json** version field (fallback)
5. **composer.json** version field (fallback)
6. **"1.0.0-dev"** (final fallback)

## Release Process

### Automatic (Recommended)
1. Create feature branch: `git checkout -b feat/new-feature`
2. Make changes and commit with conventional format
3. Create merge request to `main` or `develop`
4. Merge triggers automatic version bump and release

### Manual Release
If you need to trigger a release manually:

```bash
# Install semantic-release globally
npm install -g semantic-release

# Run semantic release
semantic-release
```

## Changelog Generation

Changelogs are automatically generated based on commit messages:

- **Features** are listed under "Features" 
- **Bug Fixes** are listed under "Bug Fixes"
- **Breaking Changes** are highlighted prominently
- **Performance Improvements** get their own section
- Documentation and chore commits are usually excluded

## Troubleshooting

### No Release Created
- Check commit messages follow conventional format
- Ensure commits contain releasable changes (feat/fix/BREAKING CHANGE)
- Verify GitLab token permissions

### Version Not Updated in Application
- Check if `APP_VERSION` is set in environment
- Verify VERSION file exists in deployment
- Ensure `app_version()` helper is working correctly

### Build Artifacts Wrong Version
- Check if semantic-release ran successfully
- Verify VERSION file was created and contains correct version
- Check build stage logs for version detection

## Best Practices

1. **Write Clear Commit Messages**: Follow conventional format consistently
2. **Atomic Commits**: One logical change per commit
3. **Test Before Merge**: Ensure all tests pass in CI/CD
4. **Review Breaking Changes**: Carefully consider major version bumps
5. **Document Changes**: Include relevant details in commit body
6. **Regular Releases**: Don't accumulate too many changes between releases

## Integration with Deployment

The semantic versioning system integrates with:
- **Docker**: Version tags for container images
- **Health Checks**: Version endpoint for monitoring
- **Rollback**: Version-specific deployment archives
- **Monitoring**: Version tracking in logs and metrics
- **Documentation**: Automated changelog updates
