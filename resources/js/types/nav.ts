export type NavKind = 'endpoint' | 'pipeline' | 'step';

export interface NavLeaf {
    type: 'item';
    slug: string;
    label: string;
    href: string;
    kind: NavKind;
}

export interface NavGroup {
    type: 'group';
    group: string;
    items: NavNode[];
}

export type NavNode = NavLeaf | NavGroup;

