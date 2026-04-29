# Profile Edit Feature - Implementation Steps

## Progress Tracker

### [x] 1. Database Schema Update
- User confirmed: `ALTER TABLE users ADD COLUMN photo VARCHAR(255) DEFAULT NULL;` executed.

### [x] 2. Infrastructure Setup
- Create `assets/uploads/profiles/` directory
- Set permissions: chmod 755

### [x] 3. Update classes/User.php
- Add `updateProfile(int $id, string $name, string $email, ?string $photo): bool`
- Handle validation, email uniqueness, return success

### [x] 4. Core UI & Logic - pages/profile.php
- Handle POST update_profile
- Add photo upload processing
- Toggle edit form with prefilled inputs
- Update avatar display (img if photo exists)
- Add success/error alerts

### [x] 5. Styling - assets/css/style.css
- Add profile photo preview/upload styles
- Ensure responsive avatar img

### [x] 6. JavaScript - Inline in profile.php
- Image preview on file select
- Bootstrap collapse for form toggle

### [ ] 7. Testing
- Edit name/email
- Upload valid photo (jpg/png <2MB)
- Verify DB update, file save, UI refresh
- Test no photo, invalid file
- Check other pages unaffected

### [ ] 8. Final Cleanup
- Update TODO.md to completed
- attempt_completion

**Current Step: 4 (pages/profile.php)**
