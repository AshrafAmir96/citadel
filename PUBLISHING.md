# Publishing Citadel to Packagist

This guide will help you publish your Citadel Laravel boilerplate to Packagist.

## 📋 Prerequisites

1. **GitHub Repository**: Your code must be in a public GitHub repository
2. **Packagist Account**: Create an account at [packagist.org](https://packagist.org)
3. **GitHub Token**: Generate a personal access token for auto-updating

## 🚀 Step-by-Step Publishing Process

### 1. Prepare Your Repository

**Update composer.json** (already done):
- Changed package name from `laravel/laravel` to `your-username/citadel-laravel-boilerplate`
- Added comprehensive description and keywords
- Added author information and support links

**Replace placeholders in composer.json**:
```bash
# Update these placeholders with your actual information:
- your-username → your GitHub username
- Your Name → your actual name  
- your-email@example.com → your email
- your-website.com → your website (optional)
```

### 2. Create GitHub Repository

```bash
# Initialize git repository (if not already done)
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Citadel Laravel boilerplate"

# Create GitHub repository and push
# (Follow GitHub's instructions for creating a new repository)
git remote add origin https://github.com/your-username/citadel-laravel-boilerplate.git
git branch -M main
git push -u origin main
```

### 3. Tag Your Release

```bash
# Create and push a version tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### 4. Submit to Packagist

1. **Go to Packagist**: Visit [packagist.org](https://packagist.org)
2. **Sign in**: Use your GitHub account
3. **Submit Package**: Click "Submit" in the top menu
4. **Enter Repository URL**: `https://github.com/your-username/citadel-laravel-boilerplate`
5. **Submit**: Click "Check" and then "Submit"

### 5. Set Up Auto-Update (Recommended)

1. **Generate GitHub Token**:
   - Go to GitHub Settings → Developer settings → Personal access tokens
   - Generate token with `repo` and `user:email` scopes

2. **Configure Packagist**:
   - Go to your package page on Packagist
   - Click "Settings" 
   - Add your GitHub API token
   - Enable auto-update

3. **Set Up GitHub Webhook** (Optional but recommended):
   - Go to your repository settings on GitHub
   - Add webhook: `https://packagist.org/api/github?username=YOUR_PACKAGIST_USERNAME`

## 📝 Package Naming Guidelines

**Current name**: `your-username/citadel-laravel-boilerplate`

**Alternative suggestions**:
- `your-username/laravel-citadel`
- `your-username/laravel-api-boilerplate`
- `your-username/laravel-backend-starter`
- `your-username/citadel-api`

## 🏷️ Version Management

**Semantic Versioning** (SemVer):
- `1.0.0` - Major version (breaking changes)
- `1.1.0` - Minor version (new features)
- `1.0.1` - Patch version (bug fixes)

**Creating releases**:
```bash
# For new features
git tag -a v1.1.0 -m "Add new API endpoints"
git push origin v1.1.0

# For bug fixes  
git tag -a v1.0.1 -m "Fix authentication bug"
git push origin v1.0.1
```

## 📚 Documentation Requirements

**Essential files** (already included):
- ✅ `README.md` - Comprehensive documentation
- ✅ `LICENSE` - MIT license
- ✅ `composer.json` - Package configuration
- ✅ `.gitignore` - Git ignore rules

**Recommended additions**:
- `CHANGELOG.md` - Version history
- `CONTRIBUTING.md` - Contribution guidelines
- `SECURITY.md` - Security policy

## 🔍 Package Validation

**Before publishing, verify**:
- [ ] Package name is unique on Packagist
- [ ] All placeholders in composer.json are replaced
- [ ] README.md has installation instructions
- [ ] Repository is public and accessible
- [ ] Latest code is tagged with version

**Test installation locally**:
```bash
# Test in a fresh directory
composer create-project your-username/citadel-laravel-boilerplate test-install
```

## 🚀 Post-Publication

**Promote your package**:
1. **Update README badges**: Add Packagist download/version badges
2. **Social media**: Share on Twitter, LinkedIn, dev communities
3. **Laravel communities**: Share in Laravel Discord, Reddit r/laravel
4. **Documentation site**: Consider creating a dedicated docs site

**Monitor and maintain**:
- Respond to issues and pull requests
- Keep dependencies updated
- Release regular updates
- Monitor download statistics

## 📊 Success Metrics

**Track your package performance**:
- **Downloads**: Monthly and total downloads on Packagist
- **GitHub Stars**: Repository popularity
- **Issues/PRs**: Community engagement
- **Dependents**: Other packages using yours

## 🎯 Marketing Your Package

**Package description tips**:
- Highlight key features (OAuth2, Docker, CI/CD)
- Mention Laravel version compatibility
- Include production-ready aspects
- Use relevant keywords for searchability

**Community engagement**:
- Write blog posts about your boilerplate
- Create video tutorials
- Speak at Laravel meetups
- Contribute to Laravel ecosystem discussions

---

**Good luck with your package! 🚀**

Once published, users will be able to install your boilerplate with:
```bash
composer create-project your-username/citadel-laravel-boilerplate my-api
```
