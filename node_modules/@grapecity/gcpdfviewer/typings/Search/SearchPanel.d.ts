/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { Model, Msg, SearchResult, FindOptions } from './types';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { GcPdfViewer } from '../GcPdfViewer';
//@ts-ignore
import { PluginModel } from '@grapecity/viewer-core';
import { GcPdfSearcher } from './GcPdfSearcher';
export declare const init: () => Model;
export declare const reducer: (msg: Msg, model: Model) => Model;
declare type SearchPanelProps = {
    dispatch: (msg: Msg) => void;
    beginSearch: (searchOptions: FindOptions) => void;
    searchNext: () => void;
    cancel: (reason: any) => void;
    onResultClick: (result: SearchResult | null) => void;
    i18n: i18n;
    searcher: GcPdfSearcher;
    viewer: GcPdfViewer;
};
export declare class SearchPanel extends Component<SearchPanelProps, Model> {
    state: Model;
    componentDidMount(): void;
    componentWillUnmount(): void;
    onClose(): void;
    private onTextChange;
    private onKeyPress;
    private onCheck;
    close(): void;
    open(): void;
    setOptions(options: FindOptions): void;
    beginSearch(searchOptions?: FindOptions): Promise<void>;
    resetResults(): void;
    private onSearchBtnClick;
    private onMoreBtnClick;
    private onClearBtnClick;
    private onResultClick;
    ensureSelectedResultClass(result: SearchResult): void;
    outerDiv: HTMLDivElement | null;
    render(): JSX.Element | null;
    getResultId(result: SearchResult): string;
}
export declare const register: (viewer: GcPdfViewer, i18n: i18n, outRef: {
    searchPanel?: SearchPanel;
}) => PluginModel.PanelHandle;
export {};
