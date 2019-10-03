export const ACCOUNT_PATH_SEPARATOR = ' / ';

export const AccountTitleType = {
  REVENUE: 'REVENUE',
  EXPENSE: 'EXPENSE',
  ASSET: 'ASSET',
  LIABILITY: 'LIABILITY',
  NET_ASSET: 'NET_ASSET',
  OTHER: 'OTHER',
};

export const FinancialStatementType = {
  BALANCE_SHEET: 'BALANCE_SHEET',
  PROFIT_AND_LOSS_STATEMENT: 'PROFIT_AND_LOSS_STATEMENT',
};

export const AccountTitleTypeDesc = {
  [AccountTitleType.REVENUE]: {
    order: 10,
    isDebitSide: false,
    statements: {
      [FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]: true,
    },
  },
  [AccountTitleType.EXPENSE]: {
    order: 20,
    isDebitSide: true,
    statements: {
      [FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]: true,
    },
  },
  [AccountTitleType.ASSET]: {
    order: 30,
    isDebitSide: true,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.LIABILITY]: {
    order: 40,
    isDebitSide: false,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.NET_ASSET]: {
    order: 50,
    isDebitSide: false,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.OTHER]: {
    isDebitSide: false,
    order: 60,
    statements: {},
  },
};
