#!/bin/bash

# CI/CD Platform Switcher for Citadel
# This script helps switch between GitLab CI/CD and GitHub Actions configurations

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_color() {
    printf "${1}${2}${NC}\n"
}

# Function to show usage
usage() {
    echo "Usage: $0 {gitlab|github|status}"
    echo ""
    echo "Commands:"
    echo "  gitlab   - Configure for GitLab CI/CD"
    echo "  github   - Configure for GitHub Actions"
    echo "  status   - Show current CI/CD configuration"
    echo ""
    echo "This script will:"
    echo "  - Set up the appropriate semantic-release configuration"
    echo "  - Enable/disable CI/CD files"
    echo "  - Update documentation references"
    exit 1
}

# Function to check current status
check_status() {
    print_color $BLUE "🔍 Checking current CI/CD configuration..."
    echo ""
    
    # Check for GitLab CI/CD
    if [ -f ".gitlab-ci.yml" ] && [ ! -f ".gitlab-ci.yml.disabled" ]; then
        print_color $GREEN "✅ GitLab CI/CD: ACTIVE"
        if [ -f ".releaserc.json" ]; then
            print_color $GREEN "✅ GitLab Semantic Release: CONFIGURED"
        fi
    else
        print_color $YELLOW "⚪ GitLab CI/CD: INACTIVE"
    fi
    
    # Check for GitHub Actions
    if [ -f ".github/workflows/ci-cd.yml" ] && [ ! -f ".github/workflows/ci-cd.yml.disabled" ]; then
        print_color $GREEN "✅ GitHub Actions: ACTIVE"
        if [ -f ".releaserc.github.json" ]; then
            print_color $GREEN "✅ GitHub Semantic Release: CONFIGURED"
        fi
    else
        print_color $YELLOW "⚪ GitHub Actions: INACTIVE"
    fi
    
    # Check for conflicts
    gitlab_active=$([ -f ".gitlab-ci.yml" ] && [ ! -f ".gitlab-ci.yml.disabled" ] && echo "true" || echo "false")
    github_active=$([ -f ".github/workflows/ci-cd.yml" ] && [ ! -f ".github/workflows/ci-cd.yml.disabled" ] && echo "true" || echo "false")
    
    if [ "$gitlab_active" = "true" ] && [ "$github_active" = "true" ]; then
        print_color $RED "⚠️  WARNING: Both CI/CD systems are active! This may cause conflicts."
    fi
    
    echo ""
}

# Function to configure for GitLab
configure_gitlab() {
    print_color $BLUE "🦊 Configuring for GitLab CI/CD..."
    
    # Enable GitLab CI/CD
    if [ -f ".gitlab-ci.yml.disabled" ]; then
        mv ".gitlab-ci.yml.disabled" ".gitlab-ci.yml"
        print_color $GREEN "✅ Enabled .gitlab-ci.yml"
    fi
    
    # Set up GitLab semantic release config
    if [ -f ".releaserc.json" ]; then
        print_color $GREEN "✅ GitLab semantic release already configured"
    else
        print_color $RED "❌ .releaserc.json not found! Please ensure GitLab semantic release is set up."
    fi
    
    # Disable GitHub Actions
    if [ -f ".github/workflows/ci-cd.yml" ]; then
        mv ".github/workflows/ci-cd.yml" ".github/workflows/ci-cd.yml.disabled"
        print_color $YELLOW "⚪ Disabled GitHub Actions workflow"
    fi
    
    print_color $GREEN "✅ GitLab CI/CD configuration complete!"
    echo ""
    print_color $BLUE "📋 Next steps:"
    echo "1. Push to GitLab repository"
    echo "2. Configure GitLab CI/CD variables in project settings"
    echo "3. Set up deployment SSH keys"
    echo "4. Review .gitlab-ci.yml for your specific needs"
}

# Function to configure for GitHub
configure_github() {
    print_color $BLUE "🐙 Configuring for GitHub Actions..."
    
    # Enable GitHub Actions
    if [ -f ".github/workflows/ci-cd.yml.disabled" ]; then
        mv ".github/workflows/ci-cd.yml.disabled" ".github/workflows/ci-cd.yml"
        print_color $GREEN "✅ Enabled GitHub Actions workflow"
    fi
    
    # Set up GitHub semantic release config
    if [ -f ".releaserc.github.json" ]; then
        print_color $GREEN "✅ GitHub semantic release already configured"
    else
        print_color $RED "❌ .releaserc.github.json not found! Please ensure GitHub semantic release is set up."
    fi
    
    # Disable GitLab CI/CD
    if [ -f ".gitlab-ci.yml" ]; then
        mv ".gitlab-ci.yml" ".gitlab-ci.yml.disabled"
        print_color $YELLOW "⚪ Disabled GitLab CI/CD"
    fi
    
    print_color $GREEN "✅ GitHub Actions configuration complete!"
    echo ""
    print_color $BLUE "📋 Next steps:"
    echo "1. Push to GitHub repository"
    echo "2. Configure GitHub Secrets and Variables in repository settings"
    echo "3. Set up deployment SSH keys"
    echo "4. Create staging and production environments"
    echo "5. Review .github/workflows/ci-cd.yml for your specific needs"
}

# Function to create backup
create_backup() {
    BACKUP_DIR=".ci-backup-$(date +%Y%m%d_%H%M%S)"
    mkdir -p "$BACKUP_DIR"
    
    # Backup existing files
    [ -f ".gitlab-ci.yml" ] && cp ".gitlab-ci.yml" "$BACKUP_DIR/"
    [ -f ".github/workflows/ci-cd.yml" ] && cp ".github/workflows/ci-cd.yml" "$BACKUP_DIR/"
    [ -f ".releaserc.json" ] && cp ".releaserc.json" "$BACKUP_DIR/"
    [ -f ".releaserc.github.json" ] && cp ".releaserc.github.json" "$BACKUP_DIR/"
    
    print_color $BLUE "📁 Backup created in $BACKUP_DIR"
}

# Main script logic
case "${1:-}" in
    gitlab)
        create_backup
        configure_gitlab
        check_status
        ;;
    github)
        create_backup
        configure_github
        check_status
        ;;
    status)
        check_status
        ;;
    *)
        usage
        ;;
esac

print_color $BLUE "🔗 Documentation:"
echo "  - GitLab CI/CD: See .gitlab-ci.yml and SEMANTIC_VERSIONING.md"
echo "  - GitHub Actions: See .github/workflows/ci-cd.yml and GITHUB_ACTIONS.md"
