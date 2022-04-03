import Crud from './modules/crud.js';
(function ($) {
    function addEvenClassToTr(type) {
        let counter = 0;
        $(`.${type}`).each(function () {
            if (counter % 2 == 0) {
                $(this).addClass('even');
            }
            counter += 1;
        });
    }
    function renderTransactionsTable() {
        const scriptFolder = $('body').attr('data-script-folder');
        const transactionsRoutes = `${document.location.origin}/${scriptFolder}/api/transactions`;
        $('#transactions').DataTable({
            destroy: true,
            deferRender: true,
            columns: [
                { data: 'id' },
                { data: 'type' },
                { data: 'product_id' },
                { data: 'product_name' },
                { data: 'stage' },
                { data: 'value' },
                { data: 'desc' },
                { data: 'created_at' },
                { data: 'user_id' },
            ],
            ajax: {
                url: transactionsRoutes,
                dataSrc: "",
                "contentType": 'application/json; charset=utf-8',
                "dataType": 'json',
            },
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/ru.json',
            },
            'order': [[0, 'desc']],
            stateSave: true,
            'scrollX': true,
            dom: 'Blfrtip',
            buttons: [
                'excel', 'print',
            ],
        });
    }
    $(document).ready(function () {
        $.ajax({
            url: 'products',
            success: function (data) {
                console.log('AJAX call was successful!');
            },
            error: function (jX, err, errT) {
                console.log(jX.status + '\n' + err + '\n' + errT);
            },
        }).done(function (data) {
            $('.content').html(data);
            let stockData = $('.stock-data').text();
            stockData = JSON.parse(stockData);
            let stockStages = $('.stock-stages').text();
            stockStages = JSON.parse(stockStages);
            const stockRolesJson = $('.stock-roles').attr('data-roles');
            const stockRoles = JSON.parse(stockRolesJson);
            initStockBlock(stockData, stockStages, stockRoles);
            addEvenClassToTr('category-tr');
            addEvenClassToTr('product-tr');
            addEvenClassToTr('sub-category-tr');
            $('.category-tr .dropdown-link').first().trigger('click');
            renderTransactionsTable();
            $('.transactions-table').fadeIn();
        }).fail(function (xhr, status, error) {
            console.log(xhr, status, error);
        });
        function updateElementDisplayValue($display, value) {
            const displayText = $display.text();
            const displayValue = parseInt(displayText);
            if (!isNaN(displayValue)) {
                $display.text(displayValue + value);
            }
        }
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
                    const tdValue = parseInt($td.find('.product-value').text());
                    total += tdValue;
                });
                let tdText = $this.text();
                if (isNaN(total)) {
                }
                $this.text(total);
            });
        }
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
                    const tdValue = parseInt($td.find('.product-value').text());
                    total += tdValue;
                });
                let tdText = $this.text();
                if (isNaN(total)) {
                }
                $this.text(total);
            });
        }
        function updateProductDisplayValue($input, value) {
            const $display = $input.parents('td').find('.product-value');
            updateElementDisplayValue($display, value);
        }
        function updateStageTotal($input, value) {
            const tdIndex = $input.parents('td').index();
            const $stageTd = $('tr.total td').eq(tdIndex);
            updateElementDisplayValue($stageTd, value);
        }
        function updateProductEvents($input, value) {
            updateProductDisplayValue($input, value);
            updateStageTotal($input, value);
            calcTotalSubCategories();
            calcTotalCategories();
        }
        async function processCrud($btn, $input, value) {
            const productId = parseInt($input.attr('data-product-id'));
            const stageId = $input.attr('data-stock-stage-id');
            if (!Crud.confirm($btn, value)) {
                return;
            }
            $btn.prop('disabled', true);
            const $productInput = $btn.parents('.product-input');
            const productInputHtml = $productInput.html();
            $productInput.append(`
            <div id="loading-bar-spinner" class="spinner"><div class="spinner-icon"></div></div>
            `);
            await Crud.update(productId, stageId, value);
            if (stageId === 'in_stock' && value > 0) {
                await Crud.update(productId, 'in_production', -value);
                let $inProductionInput = $input.parents('tr').find('td.in_production input');
                if ($inProductionInput.length === 0 || !$inProductionInput) {
                    $inProductionInput = $input.parents('tr').find('td.in_production span');
                }
                updateProductDisplayValue($inProductionInput, -value);
                updateStageTotal($inProductionInput, -value);
            }
            updateProductEvents($input, value);
            $input.val('');
            $btn.prop('disabled', false);
            $('#loading-bar-spinner').remove();
            const $td = $input.parents('td');
            $td.find('.product-input').hide();
            $td.find('.show-product-input').show();
            renderTransactionsTable();
        }
        function initStockBlock(stockData, stockStages, stockRoles) {
            renderStock(stockData, stockStages, stockRoles);
            $('.add').on('click', async function () {
                const $input = $(this).prev();
                const value = parseInt($input.val());
                if (!isNaN(value) && value !== 0) {
                    await processCrud($(this), $input, value);
                }
            });
            $('.sub').on('click', async function () {
                if ($(this).next().val() > 0) {
                    const $input = $(this).next();
                    const value = parseInt($input.val());
                    if (!isNaN(value) && value !== 0) {
                        await processCrud($(this), $input, -value);
                    }
                }
            });
            $('.show-product-input').on('click', function () {
                const $el = $(this);
                const $wrapper = $el.parents('td').find('.product-input');
                $wrapper.show();
                const $input = $wrapper.find('input');
                $input.focus();
                if (!$input.hasClass('enterEventAdded')) {
                    $input.on('keyup', function (e) {
                        if (e.keyCode == 13) {
                            $(this).parents('span').find('button.add').trigger("click");
                        }
                    });
                    $input.addClass('enterEventAdded');
                }
                $el.hide();
            });
            function showStageInput(stageId, roles) {
                if (stageId === 'in_production' && roles.includes('production_role')) {
                    return true;
                }
                if (stageId === 'in_stock' && roles.includes('stock_role')) {
                    return true;
                }
                if (roles.includes('admin')) {
                    return true;
                }
                return false;
            }
            function renderStock(stockData, stockStages, stockRoles) {
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
                    if (category.includes('###')) {
                        let categoryIds;
                        [categoryName, categoryIds] = category.split('###');
                        trClass = 'category-tr expandable';
                        if (categoryIds.includes('-')) {
                            let categoryParentId, categoryId;
                            [categoryParentId, categoryId] = categoryIds.split('-');
                            trClass = 'sub-category-tr expandable';
                            dataParentId = categoryParentId.trim();
                            dataCategoryId = categoryId.trim();
                        }
                        else {
                            dataCategoryId = categoryIds.trim();
                        }
                        if (categoryIds.includes('%')) {
                            let categoryParentId, subCategoryId;
                            [categoryParentId, subCategoryId] = categoryIds.split('%');
                            trClass = 'product-tr';
                            dataParentId = categoryParentId.trim();
                            dataSubCategoryId = subCategoryId.trim();
                            if (dataParentId === dataSubCategoryId) {
                                dataSubCategoryId = '';
                            }
                            dataCategoryId = '';
                        }
                    }
                    else {
                        categoryName = category;
                    }
                    let counter = 0;
                    if (!dataParentId && !dataCategoryId && !dataSubCategoryId) {
                        if (categoryName !== 'Итого') {
                            trClass += 'product-tr product-wo-category-tr';
                        }
                        else {
                            trClass += 'total';
                        }
                    }
                    let productIdAttr = '';
                    let productId;
                    if ('product' in categoryStock) {
                        productId = categoryStock['product']['id'];
                        productIdAttr = `data-product-id="${productId}"`;
                    }
                    result += `<tr class="${trClass}" ${productIdAttr} data-parent-id="${dataParentId || ''}" data-category-id="${dataCategoryId ||
                        ''}" data-subcategory-id="${dataSubCategoryId || ''}" >`;
                    let dropdownLinkHtml = '<div class="dropdown-link"></div>';
                    if (trClass === 'product-tr' || (!dataParentId && !dataCategoryId && !dataSubCategoryId)) {
                        dropdownLinkHtml = '';
                    }
                    result += `<td ><div class="cell-wrap">${categoryName}${dropdownLinkHtml}</div></td>`;
                    let buttonMinus = '';
                    if (stockRoles.includes('admin')) {
                        buttonMinus = '<button type="button" id="sub" class="sub">-</button>';
                    }
                    let buttonPlus = '<button type="button" id="add" class="add">+</button>';
                    for (let date in categoryStock) {
                        let inputSpanWithButtons = '';
                        let cellHtml;
                        if (counter >= start && counter < end) {
                            const stageId = stockStages[counter]['id'];
                            if (categoryName === 'Итого') {
                                cellHtml = categoryStock[date]['count'];
                            }
                            else {
                                if (showStageInput(stageId, stockRoles)) {
                                    inputSpanWithButtons = `<span class="show-product-input">➕</span><span class="product-input">${buttonMinus}
    <input type="number" min="0" pattern="[0-9]" ${productIdAttr} id="${productId}-${counter}" value="" data-stock-stage-id="${stockStages[counter]['id']}" class="field product-input-field" />
    <button type="button" id="add" class="add">+</button></span>`;
                                }
                                cellHtml = `<span class="product-value">${categoryStock[date]['count']}</span>${inputSpanWithButtons}`;
                            }
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
                $('.stock-block__content').html(result);
                $('tr').each(function () {
                    const $this = $(this);
                    const parentId = $this.attr('data-parent-id');
                    if (parentId && parentId !== '') {
                        $this.hide();
                    }
                });
                calcTotalCategories();
                calcTotalSubCategories();
                $('.dropdown-link').on('click', function (e) {
                    const $tr = $(this).parents('tr');
                    const $this = $(this);
                    let action;
                    if (!$this.hasClass('opened')) {
                        action = 'expand';
                        $this.toggleClass('opened');
                    }
                    else {
                        action = 'hide';
                        $this.toggleClass('opened');
                    }
                    const categoryId = $tr.attr('data-category-id');
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
