/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import GcPdfViewer from '..';
import { LayersModel } from './types';
export declare type LayersProps = {
    viewer: GcPdfViewer;
};
export declare class Layers extends Component<LayersProps, LayersModel> {
    private _mounted;
    componentDidMount(): void;
    componentWillUnmount(): void;
    private renderLayerOption;
    render(): JSX.Element;
}
