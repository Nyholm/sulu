// @flow
import React from 'react';
import {action, observable} from 'mobx';
import {Dialog} from 'sulu-admin-bundle/components';
import {Requester} from 'sulu-admin-bundle/services';
import {buildQueryString, translate} from 'sulu-admin-bundle/utils';

export default class CacheClearToolbarAction {
    static clearCacheEndpoint: string;

    requestParameters: Object;
    @observable cacheClearing = false;
    @observable showDialog = false;

    constructor(requestParameters: Object) {
        this.requestParameters = requestParameters;
    }

    getNode() {
        return (
            <Dialog
                cancelText={translate('sulu_admin.cancel')}
                confirmLoading={this.cacheClearing}
                confirmText={translate('sulu_admin.ok')}
                onCancel={this.handleCancel}
                onConfirm={this.handleConfirm}
                open={this.showDialog}
                title={translate('sulu_website.cache_clear_warning_title')}
            >
                {translate('sulu_website.cache_clear_warning_text')}
            </Dialog>
        );
    }

    getToolbarItemConfig() {
        return {
            icon: 'su-paint',
            label: translate('sulu_website.cache_clear'),
            onClick: action(() => {
                this.showDialog = true;
            }),
            type: 'button',
        };
    }

    @action handleCancel = () => {
        this.showDialog = false;
    };

    @action handleConfirm = () => {
        this.cacheClearing = true;

        const url = CacheClearToolbarAction.clearCacheEndpoint + buildQueryString(this.requestParameters);

        Requester.delete(url).then(action(() => {
            this.showDialog = false;
            this.cacheClearing = false;
        }));
    };
}
