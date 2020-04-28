import { Direction } from '../typings';
interface IPosObject {
    x: number;
    y: number;
}
export declare const getSize: (value: string | number) => string;
/** Get the distance of the element from the top/left of the page */
export declare const getOffset: (elem: HTMLDivElement) => IPosObject;
/**
 * Get the position of the mouse/finger in the element
 * @param e Trigger event
 * @param elem Container element
 * @param isReverse From the right/bottom
 */
export declare const getPos: (e: MouseEvent | TouchEvent, elem: HTMLDivElement, isReverse: boolean) => IPosObject;
export declare type HandleFunction = (index: number) => number;
export declare const getKeyboardHandleFunc: (e: KeyboardEvent, params: {
    direction: Direction;
    max: number;
    min: number;
    hook: (e: KeyboardEvent) => boolean | HandleFunction;
}) => HandleFunction | null;
export {};
