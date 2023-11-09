/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { PageInfo } from './types';
import { PluginModel } from '../../api';
export declare type PageProps = {
    pageIndex: number;
    pageModel: PageInfo | null;
    zoomFactor: number;
    defaultPageSize?: PluginModel.PageSize;
};
export declare class Page extends Component<PageProps> {
    render(): JSX.Element;
}
