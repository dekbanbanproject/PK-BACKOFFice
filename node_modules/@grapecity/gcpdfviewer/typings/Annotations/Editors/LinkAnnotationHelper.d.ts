//@ts-ignore
import { PropertyDescriptor } from "@grapecity/core-ui";
import { LinkAnnotation, LinkDestinationType, LinkType } from "../AnnotationTypes";
export declare function findLinkPageNumber(linkAnnotation: LinkAnnotation, viewer: any): number;
export declare function findDestinationType(linkAnnotation: LinkAnnotation): LinkDestinationType;
export declare function findLinkType(linkAnnotation: LinkAnnotation): LinkType;
export declare function setLinkAnnotationProperty(descriptor: PropertyDescriptor, value: any, linkAnnotation: LinkAnnotation, viewer: any): void;
export declare function ensureLinkAnnotationProperties(linkAnnotation: LinkAnnotation, viewer: any): void;
