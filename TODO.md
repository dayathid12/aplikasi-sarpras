# TODO: Remove Trash Icon from Entry Pengeluaran Edit Page Table

## Completed Tasks
- [x] Analyzed the codebase to locate the trash icon (DeleteAction) in RincianPengeluaranRelationManager.php
- [x] Removed DeleteAction::make() from the table actions array
- [x] Removed the unused import for DeleteAction

## Remaining Tasks
- [ ] Test the edit page at http://127.0.0.1:8000/app/entry-pengeluarans/1/edit to verify the trash icon is removed
- [ ] Ensure the table still functions correctly without the delete action
