// editor-init.js

function initializeEditor() {
    console.log("✔️ [Шаг 3] Вызвана функция initializeEditor(). Все зависимости на месте.");

    const editorHolder = document.getElementById('editorjs');
    if (!editorHolder) {
        console.error("❌ КРИТИЧЕСКАЯ ОШИБКА: Элемент #editorjs не найден на странице!");
        return;
    }
    console.log("✔️ [Шаг 4] Элемент #editorjs найден.");

    try {
        console.log("⏳ [Шаг 5] Пытаюсь создать экземпляр EditorJS...");
        const editor = new EditorJS({
            holder: 'editorjs',
            placeholder: 'Начните писать здесь или нажмите "/" для добавления блока',
            minHeight: 100, // Минимальная высота редактора
            tools: {
                header: {
                    class: Header,
                    placeholder: 'Введите заголовок',
                },
                list: {
                    class: EditorjsList,
                    placeholder: 'Введите пункт списка',
                },
                paragraph: {
                    placeholder: 'Введите текст',
                },
                // Если у вас есть ImageTool, убедитесь, что он подключен и настроен здесь
                // image: {
                //     class: ImageTool,
                //     config: {
                //         uploader: {
                //             uploadByFile: '/admin/upload-image', // Ваш эндпоинт для загрузки
                //             uploadByUrl: '/admin/fetch-image-by-url' // Ваш эндпоинт для URL
                //         }
                //     }
                // }
            },
            data: window.editorData || {}
        });
        console.log("✅ [Шаг 6] Экземпляр EditorJS успешно создан!");

        const form = document.getElementById('content-form');
        const output = document.getElementById('content_json_output');

        form.addEventListener('submit', function(event) {
            console.log("🚀 [Событие] Нажата кнопка 'Сохранить'.");
            event.preventDefault();
            editor.save().then((outputData) => {
                console.log("✔️ Данные из редактора успешно получены:", outputData);
                output.value = JSON.stringify(outputData);
                console.log("✔️ JSON сохранен в скрытое поле. Отправляю форму...");
                form.submit();
            }).catch((error) => {
                console.error('❌ Ошибка сохранения данных из редактора: ', error);
                alert('Ошибка сохранения контента!');
            });
        });

    } catch (e) {
        console.error("❌ КРИТИЧЕСКАЯ ОШИБКА при инициализации EditorJS: ", e);
    }
}

function dependencyChecker() {
    console.log("---");
    console.log("⏳ [Шаг 2] Проверяю зависимости...");

    const editorJsDefined = typeof EditorJS !== 'undefined';
    const headerDefined = typeof Header !== 'undefined';
    const listDefined = typeof EditorjsList !== 'undefined';
    // const imageToolDefined = typeof ImageTool !== 'undefined'; // Если используете ImageTool, раскомментируйте

    console.log(`- EditorJS: ${editorJsDefined ? '✅ Найден' : '❌ Не найден'}`);
    console.log(`- Header: ${headerDefined ? '✅ Найден' : '❌ Не найден'}`);
    console.log(`- List: ${listDefined ? '✅ Найден' : '❌ Не найден'}`);
    // console.log(`- ImageTool: ${imageToolDefined ? '✅ Найден' : '❌ Не найден'}`); // Если используете ImageTool, раскомментируйте

    // Если используете ImageTool, добавьте && imageToolDefined сюда
    if (editorJsDefined && headerDefined && listDefined) {
        initializeEditor();
    } else {
        console.log("...жду 200мс и пробую снова...");
        setTimeout(dependencyChecker, 200);
    }
}

// --- НАЧАЛО ВЫПОЛНЕНИЯ СКРИПТА ---
console.log("▶️ [Шаг 1] Скрипт editor-init.js запущен.");
dependencyChecker();