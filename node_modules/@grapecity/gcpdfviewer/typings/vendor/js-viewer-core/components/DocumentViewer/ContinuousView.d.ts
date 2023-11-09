/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { PageViewProps } from './types';
declare type ContinuousViewState = {
    viewportHeight: number;
    viewportWidth: number;
    scrollTopPosition: number;
    scrollingToPage: boolean;
};
export declare class ContinuousView extends Component<PageViewProps, ContinuousViewState> {
    private _view;
    private _pageCoords;
    private _lastScrollRequestNo;
    private _moveHandler;
    constructor(props: PageViewProps);
    private checkMouseMode;
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(prevProps: PageViewProps): void;
    private getVisiblePageIndex;
    private onResize;
    private onScroll;
    private fetchPagesIfNew;
    private setCurrentPage;
    private onClick;
    render(): JSX.Element;
    private getPageSize;
    private getPageViewportSize;
}
export {};
