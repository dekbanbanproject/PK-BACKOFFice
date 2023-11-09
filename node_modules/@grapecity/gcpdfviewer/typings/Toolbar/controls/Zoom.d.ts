/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { ZoomSettings } from '@grapecity/viewer-core';
import GcPdfViewer from '../../GcPdfViewer';
export declare type ZoomControlProps = {
    onChange?: (zoom: ZoomSettings) => void;
    viewer: GcPdfViewer;
};
export declare type ZoomControlModel = {
    zoom?: ZoomSettings;
    openDropdown: boolean;
    disabled?: boolean;
};
export declare class ZoomControl extends Component<ZoomControlProps, ZoomControlModel> {
    _mounted: boolean;
    private _unregisterViewerStateChange?;
    constructor(props: ZoomControlProps, context?: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    private onDecButtonClick;
    private onIncButtonClick;
    private onZoomSelect;
    render(): JSX.Element;
}
