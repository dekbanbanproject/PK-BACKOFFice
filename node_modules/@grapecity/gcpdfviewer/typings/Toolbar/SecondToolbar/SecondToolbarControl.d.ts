/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import GcPdfViewer from '../..';
import { SecondToolbarLayoutMode } from './types';
export declare type SecondToolbarControlProps = {
    viewer: GcPdfViewer;
};
export declare type SecondToolbarControlModel = {
    shown?: boolean;
    toolbarKey: SecondToolbarLayoutMode;
    hasDocument: boolean;
    supportApiEnabled: boolean;
    marginTop?: number;
};
export declare class SecondToolbarControl extends Component<SecondToolbarControlProps, SecondToolbarControlModel> {
    _mounted: boolean;
    constructor(props: SecondToolbarControlProps, context: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    onParentStateChanged(): void;
    render(): JSX.Element | null;
    get toolbarName(): string;
    get isShown(): boolean;
    set marginTop(marginTop: number);
    get marginTop(): number;
    hide(): void;
    show(toolbarKey?: SecondToolbarLayoutMode, options?: {
        marginTop?: number;
    }): void;
}
