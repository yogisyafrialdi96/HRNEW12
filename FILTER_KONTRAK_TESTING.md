# Filter Kontrak - Comprehensive Testing Guide

## 📋 Test Environment Setup

**Browser:** Chrome/Firefox latest
**Device:** Desktop (1920x1080) + Tablet (768px) + Mobile (375px)
**User Role:** Admin/Manager dengan akses ke Kontrak Karyawan

## 🧪 Test Scenarios

### TEST 1: Filter by Jenis Kontrak (TETAP)

**Steps:**
1. Navigate to Kontrak Karyawan page
2. Locate filter section at top of table
3. Click "Semua Jenis Kontrak" dropdown
4. Select "TETAP"
5. Observe table updates

**Expected Results:**
- ✅ Table reloads with loading indicator
- ✅ Only TETAP contracts displayed
- ✅ Pagination updates to show only TETAP contracts
- ✅ Other filters still available and functional
- ✅ "Sisa Kontrak" showing "Tidak terbatas" for all rows
- ✅ URL updates with filter parameter

**Fail Conditions:**
- ❌ Non-TETAP contracts still visible
- ❌ Table doesn't update
- ❌ Filter dropdown stays blank
- ❌ Pagination breaks

---

### TEST 2: Filter by Jenis Kontrak (PKWT)

**Steps:**
1. From previous test, change "TETAP" filter
2. Click "Semua Jenis Kontrak" dropdown
3. Select "PKWT"
4. Observe table updates

**Expected Results:**
- ✅ Only PKWT contracts displayed
- ✅ "Sisa Kontrak" varies (some "Sudah Berakhir", "Akan Berakhir", "Masih Berlaku")
- ✅ Previous filter removed/cleared
- ✅ Pagination updates
- ✅ Count of records matches PKWT contracts

**Fail Conditions:**
- ❌ Mixed TETAP and PKWT contracts displayed
- ❌ Filter state doesn't change
- ❌ Wrong contract types shown

---

### TEST 3: Filter by Status (Aktif)

**Steps:**
1. Reset jenis_kontrak filter to "Semua"
2. Click "Semua Status" dropdown
3. Select "Aktif"
4. Observe table

**Expected Results:**
- ✅ Only contracts with status='aktif' shown
- ✅ Status badge shows "Aktif" with green background
- ✅ Pagination shows reduced number of results
- ✅ Other filters still selectable

**Fail Conditions:**
- ❌ "Selesai", "Perpanjangan", "Dibatalkan" contracts visible
- ❌ Status column shows wrong status values

---

### TEST 4: Filter by Status (Selesai)

**Steps:**
1. Click "Semua Status" dropdown
2. Select "Selesai"
3. Observe table

**Expected Results:**
- ✅ Only contracts with status='selesai' shown
- ✅ Status badge shows "Selesai" with gray background
- ✅ "Sisa Kontrak" column shows "Sudah Berakhir"
- ✅ Pagination updates

**Fail Conditions:**
- ❌ Active contracts visible
- ❌ Wrong status badge colors

---

### TEST 5: Filter by Sisa Kontrak (Sudah Berakhir)

**Steps:**
1. Reset status filter
2. Click "Semua Sisa Kontrak" dropdown
3. Select "Sudah Berakhir"
4. Observe table
5. Check tglselesai_kontrak in "Periode & Sisa Kontrak" column

**Expected Results:**
- ✅ Only contracts where tglselesai_kontrak < today shown
- ✅ All dates in "Periode & Sisa Kontrak" are past dates
- ✅ Status column shows "Selesai"
- ✅ Badge color is red/"Sudah berakhir"

**Fail Conditions:**
- ❌ Future dates visible
- ❌ Active contracts shown
- ❌ Status not matching "Selesai"

---

### TEST 6: Filter by Sisa Kontrak (Akan Berakhir ≤30 hari)

**Steps:**
1. Click "Semua Sisa Kontrak" dropdown
2. Select "Akan Berakhir (≤30 hari)"
3. Note today's date
4. Observe tglselesai_kontrak dates

**Expected Results:**
- ✅ All dates in "Periode & Sisa Kontrak" between today and today+30 days
- ✅ No past dates (berakhir)
- ✅ No dates beyond today+30
- ✅ Badge shows "Akan Berakhir" with orange color
- ✅ Status likely shows "Aktif"

**Fail Conditions:**
- ❌ Dates outside 0-30 day range visible
- ❌ Already expired contracts visible
- ❌ Dates >30 days in future visible

---

### TEST 7: Filter by Sisa Kontrak (Masih Berlaku >30 hari)

**Steps:**
1. Click "Semua Sisa Kontrak" dropdown
2. Select "Masih Berlaku (>30 hari)"
3. Observe table

**Expected Results:**
- ✅ All dates in "Periode & Sisa Kontrak" > 30 days in future
- ✅ No dates within 30 day range
- ✅ No past dates
- ✅ Badge shows green with "X hari tersisa" where X > 30
- ✅ Status shows "Aktif"

**Fail Conditions:**
- ❌ Dates within 30 days visible
- ❌ Past dates visible
- ❌ Wrong duration calculation

---

### TEST 8: Filter by Sisa Kontrak (Tidak Terbatas)

**Steps:**
1. Click "Semua Sisa Kontrak" dropdown
2. Select "Tidak Terbatas (TETAP)"
3. Observe table

**Expected Results:**
- ✅ Only contracts with tglselesai_kontrak = NULL shown
- ✅ "Periode & Sisa Kontrak" shows "Tidak terbatas"
- ✅ Jenis Kontrak column shows "TETAP"
- ✅ Status shows "Aktif"
- ✅ No end date visible in periode column

**Fail Conditions:**
- ❌ Contracts with tglselesai_kontrak visible
- ❌ Wrong duration calculation
- ❌ Mixed PKWT contracts

---

### TEST 9: Combine Multiple Filters (TETAP + Aktif)

**Steps:**
1. Set "Semua Jenis Kontrak" → "TETAP"
2. Set "Semua Status" → "Aktif"
3. Set "Semua Sisa Kontrak" → "Tidak Terbatas"
4. Observe results

**Expected Results:**
- ✅ Only TETAP contracts with status=aktif shown
- ✅ All have tglselesai_kontrak = NULL
- ✅ All badges show "Tidak terbatas"
- ✅ Pagination shows count of matching records
- ✅ Clear result set with no extraneous records

**Fail Conditions:**
- ❌ Non-TETAP contracts visible
- ❌ Non-Aktif contracts visible
- ❌ tglselesai_kontrak values exist
- ❌ Filters interfere with each other

---

### TEST 10: Combine Multiple Filters (PKWT + Aktif + Akan Berakhir)

**Steps:**
1. Set "Semua Jenis Kontrak" → "PKWT"
2. Set "Semua Status" → "Aktif"
3. Set "Semua Sisa Kontrak" → "Akan Berakhir (≤30 hari)"
4. Observe results

**Expected Results:**
- ✅ Only PKWT contracts shown
- ✅ All with status=aktif
- ✅ tglselesai_kontrak between today and today+30
- ✅ These are contracts requiring urgent attention
- ✅ Realistic small result set (if any records match)

**Fail Conditions:**
- ❌ TETAP contracts visible
- ❌ Non-aktif status visible
- ❌ Dates outside range
- ❌ Filters conflict

---

### TEST 11: Show Deleted - Normal to Deleted View

**Steps:**
1. Clear all filters (reset to defaults)
2. Note current record count (normal view)
3. Click "Show Deleted" button
4. Observe button text change
5. Observe table

**Expected Results:**
- ✅ Button label changes to "Show Exist"
- ✅ Button color changes slightly (visual feedback)
- ✅ Table shows soft-deleted records (likely much fewer or none if no deletes yet)
- ✅ Action buttons in table change to Restore/Force Delete
- ✅ Records with deleted_at timestamps shown
- ✅ Possibly empty table if no deletes exist (OK)

**Fail Conditions:**
- ❌ Button label doesn't change
- ❌ Action buttons don't change to Restore/Force Delete
- ❌ Active records mixed with deleted
- ❌ Performance issue (table slow to load)

---

### TEST 12: Restore Deleted Contract

**Setup:**
1. Must have at least one soft-deleted contract
   - If not: delete one contract first (it becomes soft-deleted)

**Steps:**
1. Click "Show Deleted" button
2. Locate deleted contract in table
3. Hover over Sync icon (Restore button)
4. Click Sync icon
5. Confirm in modal dialog
6. Check toast notification

**Expected Results:**
- ✅ Confirmation modal appears
- ✅ Modal shows contract number being restored
- ✅ Confirm button in modal
- ✅ Contract record restored
- ✅ Toast shows "Data Kontrak berhasil dipulihkan."
- ✅ Table refreshes, record no longer in deleted view
- ✅ Click "Show Exist" to verify contract back in normal view

**Fail Conditions:**
- ❌ No confirmation dialog
- ❌ Toast shows error message
- ❌ Contract still in deleted view after restore
- ❌ Contract not visible in normal view

---

### TEST 13: Force Delete (Hard Delete) Contract

**⚠️ WARNING: This is destructive - use test data only**

**Setup:**
1. Must have at least one soft-deleted contract to test

**Steps:**
1. Click "Show Deleted" button
2. Locate test contract marked for permanent deletion
3. Hover over Trash icon
4. Click Trash icon
5. Confirm in modal dialog
6. Check toast notification

**Expected Results:**
- ✅ Confirmation modal appears with warning text
- ✅ Modal clearly shows permanent deletion warning
- ✅ Confirm button clearly labeled
- ✅ Contract permanently removed from database
- ✅ Toast shows "Data Kontrak berhasil dihapus permanent."
- ✅ Table refreshes, record no longer exists
- ✅ Record NOT recoverable (verify in database)

**Fail Conditions:**
- ❌ No warning modal
- ❌ Toast shows error
- ❌ Record still visible anywhere
- ❌ Record still in database (query it)

---

### TEST 14: Search with Filters

**Steps:**
1. Set "Semua Jenis Kontrak" → "PKWT"
2. In search box (right side), type partial nomor_kontrak or karyawan name
3. Observe table updates
4. Verify only PKWT contracts matching search shown

**Expected Results:**
- ✅ Search filters applied on top of jenis_kontrak filter
- ✅ Only matching PKWT contracts shown
- ✅ Non-matching contracts filtered out
- ✅ Search highlighting visible
- ✅ Count updated
- ✅ Results are subset of PKWT contracts

**Fail Conditions:**
- ❌ Search clears jenis_kontrak filter
- ❌ Non-PKWT records appear
- ❌ Search term not matched
- ❌ No results despite potential matches

---

### TEST 15: Sort with Filters

**Steps:**
1. Set "Semua Status" → "Aktif"
2. Click "No. Kontrak" column header to sort
3. Observe sort direction icon (up/down arrow)
4. Click again to reverse sort
5. Verify all visible records are Aktif status

**Expected Results:**
- ✅ Sort icon appears in column header
- ✅ Records sorted by nomor_kontrak ascending/descending
- ✅ Sort applied only to filtered results (Aktif records)
- ✅ Pagination reflects sort order
- ✅ All visible records still have status=Aktif
- ✅ No non-Aktif records mixed in

**Fail Conditions:**
- ❌ Sort clears status filter
- ❌ Non-Aktif records visible
- ❌ Sort doesn't work
- ❌ Sort icon missing

---

### TEST 16: Pagination with Filters

**Steps:**
1. Set "Semua Jenis Kontrak" → "TETAP"
2. Set perPage dropdown to "10"
3. If >10 TETAP records exist, multiple pages appear
4. Click page 2 link
5. Observe new records loaded
6. All should be TETAP
7. Change perPage to "25"
8. Verify page resets and shows 25 records per page

**Expected Results:**
- ✅ Pagination links appear for filtered results
- ✅ Page 2 loads correctly with more TETAP contracts
- ✅ All records on page 2 are TETAP
- ✅ Changing perPage updates display
- ✅ Page count recalculates
- ✅ Filter maintained through pagination

**Fail Conditions:**
- ❌ Pagination breaks with filters
- ❌ Non-TETAP records on page 2
- ❌ Filter resets when paginating
- ❌ perPage dropdown doesn't work

---

### TEST 17: Responsive Design - Tablet (768px)

**Setup:**
- Open DevTools (F12)
- Set viewport to 768px width
- Rotate to landscape if possible

**Steps:**
1. Navigate to Kontrak Karyawan
2. Observe filter section layout
3. Observe action buttons layout
4. Try clicking filters and buttons

**Expected Results:**
- ✅ Filters stack vertically
- ✅ Filter dropdowns full width or near-full width
- ✅ Show Deleted button visible and clickable
- ✅ Table columns scroll horizontally if needed
- ✅ No overlapping elements
- ✅ Touch-friendly button sizes
- ✅ Text readable without zooming

**Fail Conditions:**
- ❌ Filters overflow screen
- ❌ Buttons overlap
- ❌ Text too small
- ❌ Horizontal scroll needed unnecessarily

---

### TEST 18: Responsive Design - Mobile (375px)

**Setup:**
- Set viewport to 375px width (iPhone size)
- Or use actual mobile device

**Steps:**
1. Navigate to Kontrak Karyawan
2. Observe layout
3. Scroll through filters
4. Try interacting with filters
5. Check table readability

**Expected Results:**
- ✅ Each filter on separate line
- ✅ Each button on separate line  
- ✅ All elements full-width or close to it
- ✅ Readable text sizes
- ✅ Touch-friendly button sizes (min 44x44px)
- ✅ Table scrolls horizontally if needed
- ✅ No horizontal overflow on page itself

**Fail Conditions:**
- ❌ Horizontal scroll on page
- ❌ Elements cut off
- ❌ Too small to tap
- ❌ Unreadable text

---

### TEST 19: Reset Filters to Default

**Steps:**
1. Apply multiple filters (TETAP + Aktif + Tidak Terbatas)
2. Click refresh button in browser (F5)
3. Observe page reload

**Expected Results:**
- ✅ Page reloads
- ✅ Filters persist (Livewire state maintained)
- ✅ Table shows same filtered results
- ✅ OR check if URL parameters preserved
- ✅ User can explicitly reset by selecting "Semua..." options

**Alternative Reset:**
1. Clear each filter dropdown to blank/default
2. Observe all filters clear
3. Table updates to show all records

**Expected Results:**
- ✅ All dropdowns return to "Semua..." state
- ✅ Table shows all active contracts again
- ✅ Pagination resets
- ✅ Record count increases

**Fail Conditions:**
- ❌ Filters don't persist on page reload
- ❌ Can't reset filters
- ❌ Partial reset only

---

### TEST 20: No Results State

**Setup:**
- Need to create a filter combination that returns 0 results
- Example: PKWT + Status=Selesai might have no records
- OR PKWT + Expired might have no records

**Steps:**
1. Apply filters that would return no results
2. Observe table

**Expected Results:**
- ✅ "No contracts found" or similar empty state message
- ✅ Message includes helpful text like "Get started by creating a new contract"
- ✅ Table is clean (no blank rows)
- ✅ Pagination controls hidden
- ✅ Filters still visible (user can adjust)
- ✅ User not confused

**Fail Conditions:**
- ❌ Blank table with no message
- ❌ Error message displayed
- ❌ Pagination controls still show

---

## 🔍 Verification Checklist

### Backend Verification
- [ ] PHP syntax valid (php -l command)
- [ ] No SQL injection vulnerabilities (using WHERE clauses properly)
- [ ] Soft delete trait properly used
- [ ] Query performance acceptable (check database query count)
- [ ] Error handling in place for restore/forceDelete

### Frontend Verification
- [ ] Filter dropdowns populated correctly
- [ ] Filter values passed to backend
- [ ] Table updates in real-time (wire:model.live)
- [ ] No console errors (F12 DevTools)
- [ ] Blade template syntax correct
- [ ] Icons display properly (SVG rendering)

### UX Verification
- [ ] User can clearly understand filter options
- [ ] Filter results are immediate and obvious
- [ ] Empty states handled gracefully
- [ ] Mobile layout fully usable
- [ ] No performance lag when applying filters
- [ ] Error messages clear and actionable

---

## 🐛 Debugging Tips

### If filters not working:
1. Check browser console for JavaScript errors
2. Check network tab for failed API calls
3. Verify wire:model.live is present in HTML
4. Clear Livewire cache: `php artisan livewire:publish`

### If table not updating:
1. Verify filter value changed in component
2. Check if render() method called
3. Look for exception in Laravel logs
4. Test individual filter in isolation

### If restore/delete not working:
1. Verify SoftDeletes trait on model
2. Check if permission exists
3. Look for exception in logs
4. Test with known soft-deleted record

### Performance issues:
1. Check query count in Laravel Debugbar
2. Verify indexes on `tglselesai_kontrak` column
3. Test with large dataset
4. Consider query caching

---

## ✅ Final Sign-Off

**Tested By:** [Your Name]
**Date:** [Date]
**Browser/Device:** [Specify]
**Overall Result:** [ ] ✅ PASS [ ] ❌ FAIL
**Issues Found:** [List any]
**Ready for Production:** [ ] YES [ ] NO

---

**Test Documentation Complete**
