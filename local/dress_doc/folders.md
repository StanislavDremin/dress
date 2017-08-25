## Структура файлов

Все шаблоны, компоненты и модули находятся в папке /local/

#### /local/components/ab - основные кастомные компоненты 
 * /local/components/ab/auth.enter - авторизация, регистрация 
 * /local/components/ab/basket.top - верхняя корзина
 * /local/components/ab/catalog.detail - деталка товара 
 * /local/components/ab/catalog.main - комплексный каталог, сделан на основе компонента digitalwand.mvc /local/modules/digitalwand.mvc/doc/ru
 * /local/components/ab/catalog.main - комплексный каталог, сделан на основе компонента digitalwand.mvc /local/modules/digitalwand.mvc/doc/ru
 * /local/components/ab/comments - комментарии, отзывы, вопросы
 * /local/components/ab/detail.order.print - страница спасибо за заказ
 * /local/components/ab/hl.items - компонент вывода списка элементов highload блоков
 * /local/components/ab/order.creator - корзина + форма создания заказа
 * /local/components/ab/product.list - списки товаров в каталоге templates/.default и карусельки templates/caroucel
 * /local/components/ab/product.sections - список разделов каталога в виде дерева
 * /local/components/ab/search.title - поиск в шапке
 * /local/components/ab/sliders - слайдер на hl-блоках
 * /local/components/ab/smart.filter - фильтр каталога

#### /local/dist - стили, картинки, общие скрипты, js-либы и шрифты
* /local/dist/css/app.css - общие стили, файл минифицирован, возможно будет пересобираться сборщиком, трогать не рекомендую.
* ./css/custom.css - кастомные стили, подключаются после app.css, здесь нужно добивать, перебивать стили основного файла
* ./js/app.js - минифицирован, собирается через webpack из /local/resource/app.js
* ./vendor - папка js-либами

#### /local/lg - папка для своих логов
#### /local/lib - дополнительные php-либы, подключаются автолоадером по сиандарту PSR
#### /local/modules - кастомные модули, которые творить и жить помогают
* ./ab.tools - сборище утилит разного рода и пошива
* ./digitalwand.admin_helper - конструктор админки
* ./digitalwand.mvc - класс для построения адекватног комплексного компонента
* ./dresscode.main - модуль для иекущего проекта. Сделан, чтоб не плодить по разным местам проекта классы и всякие доработки общего функционала. 
Подключается в /local/php_interface/init.php. Доступен на любой странице. Все классы лежат в ./dresscode.main/lib, подключаются ватолоадером, автоматически, все названия
неймспейсов и классов по PSR
> * ./EventHandlers - классы для обработчиков
> * ./Favorite - классы для работы с избранным
> * ./InSaleImport - обертка над коннектором к InSales
> * ./CityServices.php - выбор и определение города
> * ./Config.php - общие конфиги на проект
> * ./Dadata.php - коннектор к сервису dadata
> * ./FileTable.php - описание таблицы файлов в D7
> * ./ModifierComponentsFilter.php - модификатор фильтров кастомных компонентов (списка товаров и пр) дабы не плодить идиотские global $arrFilter
* * ./esd.hl - модуль для упрощения работы с hl-блоками в админке
* * ./online1c.iblock - модуль для работы с инфоблокам в формате D7 (работает только с ИБ, св-ва которых лежат в отдельной таблице)

#### /local/resource - папка для общих js-компонентов и сборки их черех webpack
#### /local/templates - шаблоны сайта
* ./index - работает на весь сайт, шаблон на всю ширину без сайдбаров
* ./print - шаблон для распечатки какой-то инфы, сейчас пока работатет только на /personal/order/make - оплата заказа

