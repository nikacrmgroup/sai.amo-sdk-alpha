import { Eggy } from './../vendor/eggy.js';
class Crud {
    baseUrl;
    routes;
    scriptFolder;
    constructor() {
        this.baseUrl = document.location.origin;
        this.scriptFolder = $('body').attr('data-script-folder');
        this.routes = {
            update: `${this.baseUrl}/${this.scriptFolder}/api/products/update`,
        };
    }
    confirm($btn, value) {
        const productName = $btn.parents('tr').find('.cell-wrap').text().trim();
        const operationType = (value > 0) ? 'добавить' : 'отнять';
        const operationValue = (value > 0) ? value : -value;
        const confirmText = `Вы уверены, что хотите ${operationType} «${productName}» - ${operationValue} шт.?`;
        return confirm(confirmText);
    }
    async update(id, stageId, value, userId = 0) {
        const stagesDict = {
            in_stock: 'на складе',
            in_production: 'в производстве',
            in_reserve: 'в резерве',
        };
        const response = await this.postData(this.routes.update, { id, stageId, value, userId });
        if (response) {
            if (response.status === 403) {
                Eggy({
                    title: 'Ошибка обновления данных.',
                    message: response.message,
                    position: 'bottom-right',
                    duration: 30e3,
                    type: 'error',
                });
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            }
            const updateMessage = `Изменился товар с id ${id} на этапе "${stagesDict[stageId]}" на "${value}".`;
            console.log('updated!!! ' + response.message);
        }
    }
    postData(route, data) {
        const _this = this;
        const response = fetch(route, {
            method: 'post',
            body: JSON.stringify(data),
            headers: {
                'Accept': 'application/json',
            },
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            console.log('time elapsed', data.debug);
            return data;
        })
            .catch(function (error) {
            Eggy({
                title: 'Ошибка обновления данных',
                message: error,
                position: 'bottom-right',
                duration: 30e3,
                type: 'error',
            });
        });
        return response;
    }
    isJsonString = (str) => {
        try {
            let json = JSON.parse(str);
            return (typeof json === 'object');
        }
        catch (e) {
            return false;
        }
    };
}
export default new Crud();
