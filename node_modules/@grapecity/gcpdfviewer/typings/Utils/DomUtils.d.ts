export declare function getShadowRootOrDocument(elem?: HTMLElement): HTMLDocument | ShadowRoot;
export declare function isDescendant(child: Element, parent: Element): boolean;
export declare function getEventTarget(e: any): any;
export declare function isInputArea(el: HTMLElement): boolean;
export declare function classListContains(element: HTMLElement, className: string): boolean;
export declare function findSelfOrAncestor(el: HTMLElement | null, className: string): HTMLElement | null;
export declare function findSelfOrAncestorAttr(el: HTMLElement | null, attrName: string): HTMLElement | null;
export declare function findElementIndex(el: Element): number;
export declare function isTouchEventsEnabled(): boolean;
export declare function getEventCoordinates(event: MouseEvent | TouchEvent): {
    pageX: number;
    pageY: number;
    clientX: number;
    clientY: number;
};
export declare function reversePopupRotation(popupWrapperToRotate: HTMLElement, rotation: number, iconToRotate?: HTMLElement): void;
export declare function triggerMouseEvent(element: Element, eventType: string): void;
export declare function triggerFullStackClick(element: Element): void;
