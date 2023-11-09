//@ts-ignore
import { MouseMode } from '@grapecity/viewer-core';
/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import GcPdfViewer from '..';
import { FloatingBarModel, FloatingBarProps } from './types';
export declare class FloatingBar extends Component<FloatingBarProps, FloatingBarModel> {
    private _mounted;
    private _viewer;
    constructor(props: FloatingBarProps, state: FloatingBarModel);
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    render(): JSX.Element;
    onSetMouseMode(mouseMode: MouseMode): void;
    get isMounted(): boolean;
    show(viewer: GcPdfViewer): void;
    hide(): void;
}
