# Pilketos — Laravel 13 School Election System

Laravel 13 app for OSIS chairperson voting. Public voting page + admin dashboard for managing candidates, voters, tokens, and reports.

## Commands

```bash
php artisan serve              # dev server
npm run build                  # build Tailwind/Vite assets
npm run dev                    # Vite HMR dev server
vendor/bin/pint --dirty --format agent   # format changed PHP files
php artisan migrate:fresh --seed          # reset DB + seed
```

## Architecture

- **Database**: SQLite at `database/database.sqlite` (switch to MySQL via `.env` if needed)
- **Auth**: Custom session-based login (not Laravel Breeze/Fortify). Guard: `web`.
- **Admin middleware**: `auth` + `desktop` (JavaScript-based screen-width detection, not server-side)
- **Voting cap**: stored in `config.json` (not DB). Read via `json_decode(file_get_contents(base_path('config.json')))`

### Key Tables

| Table         | Purpose                                                                      |
| ------------- | ---------------------------------------------------------------------------- |
| `calon_ketua` | Candidates (id, nama, nomor, visi, misi, id_kelas, url_foto)                 |
| `hak_suara`   | Voter roll — column `nisn` actually stores voter _names_ (legacy misnomer)   |
| `kelas`       | Classes (X-1..XII-3)                                                         |
| `votes`       | Ballots (id_calon, id_nisn, created_at) — no timestamps, uses FK constraints |
| `tokens`      | Display tokens for voting station access (token, active)                     |
| `users`       | Admins (nama_lengkap, email, password) — uses `password_hash()`              |

### Model Quirks

- `Kelas`, `HakSuara`, `Token` — `public $timestamps = false` (no timestamp columns)
- `Vote` — `public $timestamps = false`, uses `created_at` manually
- All models have explicit `$table` set (non-standard pluralization)

### Frontend

- **Primary sidebar**: fixed left (`lg:w-64 lg:fixed`), main content has `lg:ml-64`
- **Secondary sidebar**: slides from right (`translate-x-full` → `translate-x-0`), only for add/edit forms, covers content temporarily
- **Notifications**: Alpine.js toast stack (top-right, auto-dismiss 5s)
- **Confirm modal**: Alpine.js component for delete confirmations
- **Voting page**: Uses SweetAlert2 + Montserrat font + custom card selection (preserved from original)
- **Charts**: Chart.js via CDN (polarArea in laporan page)

## Excel Import (PhpSpreadsheet)

Voter import reads `.xlsx`/`.xls`: skips row 1 (header), reads **column B only**, deduplicates against `hak_suara.nisn`.

## Config

```bash
pint.json          # Laravel preset, excludes "originals/"
boost.json         # Boost guidelines enabled for opencode
config.json        # Vote cap: {"haksuara": 150}
```

## Original Codebase

`originals/` contains the legacy PHP-plain code (pre-Laravel). Refer for reference only — do not modify.

</laravel-boost-guidelines>
