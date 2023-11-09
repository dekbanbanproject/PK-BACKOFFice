/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component, MouseEvent } from 'react';
import { DocumentInformation } from './DocumentInformation';
import { DocumentSecuritySummary } from '../Security/DocumentSecuritySummary';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare type PropertiesDialogProps = {
    i18n: i18n;
};
export declare class PropertiesDialog extends Component<PropertiesDialogProps, DocumentInformation> {
    private _windowResizeHandler?;
    createTabsMetaData(): void;
    rootElement: HTMLDivElement;
    constructor(props: any);
    get isVisible(): boolean;
    close(): void;
    shouldComponentUpdate(): boolean;
    show(metaDataPromise: Promise<DocumentInformation>, permissionsPromise: Promise<DocumentSecuritySummary>): void;
    hide(): void;
    onWindowSizeChanged(): void;
    onMouseUp(e: any): boolean;
    render(): JSX.Element;
    showTab(e: MouseEvent): void;
    _showTabInternal(className: string): void;
    _fillElements(fieldNames: ({
        key: string;
        label?: string;
        value?: string;
        optional?: boolean;
        noLabel?: boolean;
        legend?: string;
        fields?: any[];
    })[], state: any): JSX.Element[];
    readFonts(val: any, descKey: string): any;
    readDocSecurity(summary: DocumentSecuritySummary): JSX.Element;
    readPermissions(summary: DocumentSecuritySummary): JSX.Element;
}
