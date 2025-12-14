# ElectroFix (лабы: HTML/CSS/Bootstrap/JS/jQuery/PHP/MySQL/AJAX)

## Структура
- `index.php` — главная (есть `audio` и `video`)
- `devices.php` — устройства (jQuery AJAX + JSON, модалка)
- `issues.php` — неисправности (фильтр на JS + AJAX детали в offcanvas)
- `guides.php` — гайды (список + `fetch()` превью)
- `guide.php?id=...` — полный гайд (серверная генерация)
- `tools.php`, `about.php` — статическая страница + форма

Админка:
- `/admin/login.php` — вход
- `/admin/setup.php` — создаёт `admin/admin123` (после импорта схемы)
- `/admin/devices.php`, `/admin/issues.php`, `/admin/guides.php` — CRUD
- `/admin/uploads.php` — просмотр загрузок + заявок

API (JSON):
- `/api/devices.php`
- `/api/issues.php`
- `/api/guides.php`
- `/api/questions.php` (POST JSON)
- `/api/upload.php` (POST multipart, поле `file`, только после входа)

## Быстрый запуск (Open Server Panel / phpMyAdmin)
1) Создай БД `electrofix`
2) Импортируй `data/schema.sql`
3) (Опционально) Импортируй `data/seed.sql` чтобы были демо-данные
4) Проверь настройки в `server/config.php` (обычно `root` и пустой пароль)
5) Открой в браузере `http://localhost/<папка_проекта>/admin/setup.php`
6) Войди: `admin / admin123`

## Что закрывает требования
- HTML5: `header/nav/section/article/aside/footer` присутствуют
- CSS: общий файл `css/style.css`, используются селекторы по тегу/классу/`id` (`#toastArea`)
- Bootstrap: сетка/карточки/модалка/offcanvas/кнопки
- JS (lab 3):
  - интерактивная таблица (фильтр на `issues.php`)
  - форма + проверка + генерация страницы (`about.php` + `handleSupportForm`)
  - “javascript: URL” (ссылка на `devices.php`)
- jQuery (lab 4): `devices.php` детали подгружаются через `$.getJSON`
- PHP+MySQL (lab 5):
  - 5+ таблиц (их 7)
  - минимум 3 динамических раздела: devices/issues/guides
  - ввод/редактирование данных через формы: админка
  - авторизация: `/admin/login.php`
  - regex на сервере: проверка `name/email` в `/api/questions.php`
  - загрузка файлов: формы в админке + `/api/upload.php`
- AJAX (lab 6):
  - подгрузка деталей устройств/гайдов/неисправностей в JSON
  - удаление в админке кнопкой (асинхронно, JSON)

## Примечание
Это учебный проект. В “боевом” мире сюда бы добавили CSRF-токены, более строгие права, лимиты на загрузки и т.п.
