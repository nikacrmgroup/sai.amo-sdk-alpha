import Crud from './modules/crud.js';


(function ($) {
    /*Добавляем классы для полосатости таблицы*/
    function addEvenClassToTr(type) {
        let counter = 0;
        $(`.${type}`).each(function () {
            if (counter % 2 == 0) {
                $(this).addClass('even');
            }
            counter += 1;
        });
    }

    $(document).ready(function () {
        const baseUrl = document.location.origin;
        const scriptFolder = $('body').attr('data-script-folder');
        const productUrl = `${baseUrl}/${scriptFolder}/app/admin/products`;
        $.ajax({
            url: productUrl,

            success: function (data) {
                console.log('AJAX call was successful!');
                //console.log('Data from the server' + data);
            },
            error: function (jX, err, errT) {
                console.log(jX.status + '\n' + err + '\n' + errT);
            },
            //url: "stock.php",
        }).done(function (data) {
            $('.content').html(data);

            let stockData = $('.stock-data').text();
            stockData = JSON.parse(stockData);

            let stockStages = $('.stock-stages').text();
            stockStages = JSON.parse(stockStages);


            initStockBlock(stockData, stockStages);
            /*Добавляем классы для полосатости таблицы*/
            addEvenClassToTr('category-tr');
            addEvenClassToTr('product-tr');
            addEvenClassToTr('sub-category-tr');


            //new chartInit(stockData, monthData);

        }).fail(function (xhr, status, error) {
            console.log(xhr, status, error);
        });

        function updateElementDisplayValue($display: JQuery<HTMLElement>, value: number) {
            const displayText = $display.text();
            const displayValue = parseInt(displayText);
            $display.text(displayValue + value);
        }

        /**
         * Калькуляция итого подкатегорий
         * Для каждой td строки подкатегории находим tr товаров и суммируем
         */
        function calcTotalSubCategories() {
            $('tr.sub-category-tr').find('td').each(function (index, value) {
                const $this = $(this);
                const tdIndex = $this.index();
                if (tdIndex === 0) {
                    return;
                }

                const $trParent = $(this).parents('tr');
                const categoryId = $trParent.attr('data-category-id');
                const $productTr = $(`tr.product-tr[data-subcategory-id="${categoryId}"]`);

                let total = 0;
                $productTr.each(function () {
                    const $tr = $(this);
                    const $td = $tr.find('td').eq(tdIndex);
                    const tdValue = parseInt(<string>$td.find('.product-value').text());
                    total += tdValue;
                });
                let tdText = $this.text();
                //console.log(total);
                if (isNaN(total)) {

                }
                $this.text(total);

            });
        }

        /**
         * Калькуляция итого категорий
         * Для каждой td строки категории находим tr товаров и суммируем
         */
        function calcTotalCategories() {
            $('tr.category-tr').find('td').each(function (index, value) {
                const $this = $(this);
                const tdIndex = $this.index();
                if (tdIndex === 0) {
                    return;
                }

                const $trParent = $(this).parents('tr');
                const categoryId = $trParent.attr('data-category-id');
                const $productTr = $(`tr.product-tr[data-parent-id="${categoryId}"]`);

                let total = 0;
                $productTr.each(function () {
                    const $tr = $(this);
                    const $td = $tr.find('td').eq(tdIndex);
                    const tdValue = parseInt(<string>$td.find('.product-value').text());
                    //const tdValue = parseInt($td.text());
                    total += tdValue;
                });
                let tdText = $this.text();
                //console.log(total);
                if (isNaN(total)) {

                }
                $this.text(total);

            });
        }

        /**
         * Обновляем отображаемое число для продукта
         * @param $input
         * @param value
         */
        function updateProductDisplayValue($input: JQuery<HTMLElement>, value: number) {
            const $display = $input.parents('td').find('.product-value');
            updateElementDisplayValue($display, value);
        }

        /**
         * Обновляем итого для нужного этапа
         * @param $input
         * @param value
         */
        function updateStageTotal($input: JQuery<HTMLElement>, value: number) {
            const tdIndex = $input.parents('td').index();
            const $stageTd = $('tr.total td').eq(tdIndex);
            updateElementDisplayValue($stageTd, value);

        }

        function updateProductEvents($input: JQuery<HTMLElement>, value: number) {
            updateProductDisplayValue($input, value);
            updateStageTotal($input, value);

            calcTotalSubCategories();
            calcTotalCategories();
        }

        async function processCrud($btn: JQuery<HTMLElement>, $input: JQuery<HTMLElement>, value: number) {
            const productId = parseInt($input.attr('data-product-id'));
            const stageId = $input.attr('data-stock-stage-id');
            if (!Crud.confirm($btn, value)) {
                return;
            }
            $btn.prop('disabled', true);
            await Crud.update(productId, stageId, value, 1);
            /*Определяем, не меняется ли это этап «на складе», так как
            * кладовщик может увеличивать поля - в производство и «на складе» и когда он добавляет какое-то количество товаров «на складе», то
            * автоматически такое же количество списывается в "на производстве"
            * */
            if (stageId === 'in_stock' && value > 0) {
                await Crud.update(productId, 'in_production', -value, 1);
                const $inProductionInput = $input.parents('tr').find('td.in_production input');
                updateProductDisplayValue($inProductionInput, -value);
                updateStageTotal($inProductionInput, -value);
            }

            updateProductEvents($input, value);
            $input.val(0);
            $btn.prop('disabled', false);

        }

        function initStockBlock(stockData, stockStages) {
            const _this = this;
            renderStock(stockData, stockStages);


            $('.add').on('click', async function () {
                const $input = $(this).prev();
                const value: number = parseInt(<string>$input.val());
                if (!isNaN(value) && value !== 0) {
                    await processCrud($(this), $input, value);

                }

            });
            $('.sub').on('click', async function () {
                if ($(this).next().val() > 0) {
                    const $input = $(this).next();
                    const value: number = parseInt(<string>$input.val());
                    if (!isNaN(value) && value !== 0) {
                        await processCrud($(this), $input, -value);

                    }
                }
            });

            $('.show-product-input').on('click', function () {
                const $el = $(this);
                const $input = $el.parents('td').find('.product-input');
                $input.show();
                $el.hide();

            });


            function renderStock(stockData, stockStages) {
                /*Тут будут яблони цвести*/

                console.groupCollapsed('renderStock');

                const start = 0;
                const end = stockStages.length;

                let thStages = '';
                for (let i = 0; i < stockStages.length; i++) {
                    if (i >= start && i < end) {
                        thStages += '<th>' + stockStages[i]['title'] + '</th>';
                    }
                }
                let result = `<table><thead><tr><th></th>${thStages}</tr></thead><tbody>`;

                let tdCounter = 0;
                for (let category in stockData) {

                    const categoryStock = stockData[category];

                    let trClass = '';
                    let dataParentId, dataCategoryId, dataSubCategoryId;

                    let categoryName = '';
                    /*Проверяем, а не категория ли это с ###*/
                    if (category.includes('###')) {
                        let categoryIds;
                        [categoryName, categoryIds] = category.split('###');
                        /*Определяем, есть ли в categoryIds дефис, если есть - значит это подкатегория */
                        trClass = 'category-tr expandable';
                        if (categoryIds.includes('-')) {
                            let categoryParentId, categoryId;
                            [categoryParentId, categoryId] = categoryIds.split('-');
                            trClass = 'sub-category-tr expandable';
                            dataParentId = categoryParentId.trim();
                            dataCategoryId = categoryId.trim();
                        } else {
                            dataCategoryId = categoryIds.trim();
                        }

                        /*если продукт, то там должен быть %*/

                        if (categoryIds.includes('%')) {

                            let categoryParentId, subCategoryId;
                            [categoryParentId, subCategoryId] = categoryIds.split('%');
                            trClass = 'product-tr';

                            dataParentId = categoryParentId.trim();
                            dataSubCategoryId = subCategoryId.trim();
                            /*Проверяем, не является ли товар вложенностью одной категории*/
                            if (dataParentId === dataSubCategoryId) {
                                dataSubCategoryId = '';
                            }
                            dataCategoryId = '';
                        }
                    } else {
                        categoryName = category;
                    }

                    let counter = 0;
                    /*dataParentId, dataCategoryId, dataSubCategoryId*/
                    if (!dataParentId && !dataCategoryId && !dataSubCategoryId) {
                        if (categoryName !== 'Итого') {
                            trClass += 'product-tr product-wo-category-tr';
                        } else {
                            trClass += 'total';
                        }

                    }

                    let productIdAttr = '';
                    let productId;
                    /*Проверяем, продукт ли это, если да - то выводим его id*/
                    if ('product' in categoryStock) {
                        productId = categoryStock['product']['id'];
                        productIdAttr = `data-product-id="${productId}"`;


                    }
                    result += `<tr class="${trClass}" ${productIdAttr} data-parent-id="${dataParentId || ''}" data-category-id="${dataCategoryId ||
                    ''}" data-subcategory-id="${dataSubCategoryId || ''}" >`;

                    let dropdownLinkHtml = '<div class="dropdown-link"></div>';
                    /*проверка на продукт и итого/оборот строки*/
                    if (trClass === 'product-tr' || (!dataParentId && !dataCategoryId && !dataSubCategoryId)) {
                        dropdownLinkHtml = '';
                    }


                    result += `<td ><div class="cell-wrap">${categoryName}${dropdownLinkHtml}</div></td>`;

                    for (let date in categoryStock) {
                        let cellHtml;
                        if (counter >= start && counter < end) {

                            if (categoryName === 'Итого') {
                                /*Если Итого, то input не нужен*/
                                cellHtml = categoryStock[date]['count'];
                            } else {
                                cellHtml = `<span class="product-value">${categoryStock[date]['count']}</span><span class="show-product-input">➕</span><span class="product-input"><button type="button" id="sub" class="sub">-</button>
    <input type="number" min="0" pattern="[0-9]" ${productIdAttr} id="${productId}-${counter}" value="0" data-stock-stage-id="${stockStages[counter]['id']}" class="field product-input-field" />
    <button type="button" id="add" class="add">+</button></span>`;
                            }
                            const stageId = stockStages[counter]['id'];
                            result += `<td class="${stageId}" data-stage-id="${stageId}">` + cellHtml + '</td>';
                        }
                        counter += 1;
                    }
                    result += '</tr>';
                    tdCounter++;
                }
                result += '</tbody></table>';

                const $result = $(result);

                const lastTr = $('tbody tr:last-child', $result);

                lastTr.prev('tr').addClass('total');
                /*  $('td', lastTr).filter(':not(:first-child)').each(function () {
                      let price = parseInt(<string>$(this).find('input').val());
                      const localizedPrice = price.toLocaleString('ru-RU');

                      console.log(localizedPrice);
                      $(this).text(localizedPrice);
                      //$(this).text(price + ' грн');
                  });*/

                $('.stock-block__content').html(result);
                /*Прячем всех, у кого есть parent-id*/
                $('tr').each(function () {
                    const $this = $(this);
                    const parentId = $this.attr('data-parent-id');
                    if (parentId && parentId !== '') {
                        $this.hide();
                    }
                });


                calcTotalCategories();
                calcTotalSubCategories();

                /*Навешиваем события интерфейса и калькуляции*/
                $('.dropdown-link').on('click', function (e) {
                    /*Находим все tr, у которых parent-id равен id категории нажатой*/
                    const $tr = $(this).parents('tr');
                    const $this = $(this);
                    let action;
                    if (!$this.hasClass('opened')) {
                        action = 'expand';
                        $this.toggleClass('opened');
                    } else {
                        action = 'hide';
                        $this.toggleClass('opened');
                    }

                    const categoryId = $tr.attr('data-category-id');
                    //not($this)
                    $('tr').each(function () {
                        const $element = $(this);
                        const parentId = $element.attr('data-parent-id');
                        const subCategoryId = $element.attr('data-subcategory-id');
                        if (parentId === categoryId) {
                            if (action === 'expand' && subCategoryId === '') {
                                $element.show();
                            }
                            if (action === 'hide') {
                                $element.hide();
                                const $dropdownLink = $element.find('.dropdown-link');
                                if ($dropdownLink.hasClass('opened')) {
                                    $dropdownLink.removeClass('opened');
                                }
                            }

                        }
                        if ($tr.hasClass('sub-category-tr')) {
                            if (subCategoryId === categoryId) {
                                if (action === 'expand') {
                                    $element.show();
                                }
                                if (action === 'hide') {
                                    $element.hide();

                                }
                            }
                        }

                    });

                });
                console.groupEnd();


            }
        }

    });

}(jQuery));