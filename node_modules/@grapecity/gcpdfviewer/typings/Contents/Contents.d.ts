/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { ContentsModel, TocNode } from './types';
export declare type ContentsProps = {
    navigate: (node: TocNode) => void;
};
export declare class Contents extends Component<ContentsProps, ContentsModel> {
    private onItemClick;
    private toggleExpanded;
    private renderContents;
    render(): JSX.Element;
}
