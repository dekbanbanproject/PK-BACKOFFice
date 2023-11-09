/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { DropdownItem, Size } from '@grapecity/core-ui';
//@ts-ignore
import { ChangedEventArgs } from '@grapecity/viewer-core';
import GcPdfViewer from '../..';
import { StampCategory } from '../../Models/ViewerTypes';
import { ISupportApi } from '../../SupportApi/ISupportApi';
export declare type AddStampDropdownProps = {
    dropup?: boolean;
    size?: Size;
    viewer: GcPdfViewer;
    shortcutTip: string;
};
export declare type AddStampDropdownModel = {
    enabled?: boolean;
    checked?: boolean;
    selectedImageUrl?: string;
    selectedImageDpi?: number;
    selectedImageKey?: string;
    imagesCache?: CachedStampCategory[];
};
export declare type CachedStampCategory = {
    name: string;
    imageSrc: string[];
    imageKeys: string[];
    imageLoaded: boolean[];
    isDynamic: boolean;
    dpi: number;
};
export declare class AddStampDropdown extends Component<AddStampDropdownProps, AddStampDropdownModel> {
    private _dropDown;
    private _mounted;
    private _selectedImageObjectUrl;
    private _loadingFlag;
    private static _cacheHash;
    private static _stampsLoadPromise;
    constructor(props: AddStampDropdownProps, context: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    static clearCache(viewerInstanceId: string): void;
    private get cachedCategories();
    private set cachedCategories(value);
    _clearImagesCache(): void;
    _cacheImages(categories: StampCategory[]): Promise<void>;
    onImageLoaded(cachedCat: CachedStampCategory, index: number, imageKey: string): void;
    loadStampCategories(force?: boolean): Promise<void>;
    get selectedImageUrl(): string | undefined;
    get selectedImageKey(): string | undefined;
    private set selectedImageObjectUrl(value);
    get selectedImageDpi(): number | undefined;
    onParentStateChanged(args: ChangedEventArgs): void;
    onItemSelected(selectedImageUrl?: string, cat?: CachedStampCategory, selectedImageKey?: string): void;
    activateStampMode(): void;
    get isActivateEditModeOnMouseMoveAllowed(): boolean;
    render(): JSX.Element;
    renderSubMenu(selectedImageUrl: string | undefined, cat: CachedStampCategory, supportApi: ISupportApi | null, ddItems: DropdownItem[]): void;
}
